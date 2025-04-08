document.addEventListener("DOMContentLoaded", function(){

    const form_search = document.getElementById('form_search');

    form_search.addEventListener("submit", function(event){
        
        let covoiturage = document.getElementById('content');

        covoiturage.scrollIntoView({ behavior: "smooth", block:'start' });

        event.preventDefault();
        const formData = new FormData(form_search);
        const parameters = new URLSearchParams(formData).toString();
        fetch('/search?' + parameters, {
            method: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            covoiturage.innerHTML = "";
            covoiturage.innerHTML = data.filtresHtml;

            let resultatsActuels = [];
            
            const btnFilter = document.getElementById('btnFilter');

            if(data.covoiturages.length > 0){
                resultatsActuels = data.covoiturages;
                returnResultats(data.covoiturages);

            }else if(data.alternatives.length > 0){
                resultatsActuels = data.alternatives;
                const divtitre = document.createElement('h5');
                divtitre.classList.add('text-center', 'text-light');
                divtitre.innerHTML = 'Désolé il n\'y a pas de départ à cette date.<br>Voici les premiers covoiturages les plus proches pour votre voyage';
                covoiturage.appendChild(divtitre);
                
                returnResultats(data.alternatives);

            }else{
                covoiturage.innerHTML = '<h5 class="text-center text-light">Désolé aucun covoiturage trouvé</h5>'
                return;
            }

            btnFilter.addEventListener('click', (e) => {
                e.preventDefault();
                const filtres = Filtres();
                covoiturage.innerHTML = "";
                covoiturage.innerHTML = data.filtresHtml;
                AppliquerFiltres(resultatsActuels, filtres);
            });

            function AppliquerFiltres(result, filtres){
                const resultats = filtrerCovoiturages(result, filtres);
                returnResultats(resultats);
            }
    
            function Filtres(){
                return {
                    maxPrix: parseFloat(document.getElementById('maxPrix').value) || Infinity,
                    minNote: parseInt(document.getElementById('minNote').value) || 0,
                    maxDuree: parseFloat(document.getElementById('maxDuree').value) || Infinity,
                    ecologique : document.getElementById('ecolo').checked
                };
            }
    
            function filtrerCovoiturages(resultats, filtres){
                return resultats.filter(resultat => {
                    const arrivee = new Date(resultat.heure_arrivee);
                    const depart = new Date(resultat.heure_depart);
                    const duree = (arrivee - depart) / 6000;
                            
                        return (
                            resultat.prix_personne <= filtres.maxPrix &&
                            resultat.user.note >= filtres.minNote &&
                            (resultat.voiture.energie === "Électriques") || !filtres.ecologique &&
                            duree <= filtres.maxDuree
                    );
                });
            }

            function returnResultats(resultatsFiltres){
                resultatsFiltres.forEach(element => {
                    const cardElement = createCovoiturageCard(element);
                    covoiturage.appendChild(cardElement);
                });
            }
        
            function createCovoiturageCard(covoiturage) {
                const div = document.createElement('div');
                div.classList.add('card', 'rounded-pill', 'p-3', 'covoiturage', 'mt-3', 'mb-3', 'ms-5', 'me-5');
                div.innerHTML = `
                    <div class="row g-0">
                    <div class="col-4 text-center">
                        <div class="rounded-circle">
                            <img class="rounded-circle bg-success" src="/user/photo/${covoiturage.user.id}" alt="image utilisateur" width="100" height="100">
                        </div>
                        <div class="text-center">
                            <img src="/media/star-fill.svg" alt="star avis">
                            <p class="text-light">${covoiturage.user.note}</p>
                        </div>
                        <p class="card-text text-light">${covoiturage.user.pseudo}</p>
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="text-light m-3">${new Date(covoiturage.date_depart).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</h5>
                            <div class="d-flex">
                                <h5 class="card-title text-light">${covoiturage.lieu_depart}</h5>
                                <img src="/media/arrow-right.svg" class="rounded-circle me-3 ms-4" alt="flèche">
                                <h5 class="card-title text-light">${covoiturage.lieu_arrivee}</h5>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h2 class="card-text text-light">${covoiturage.prix_personne} Crédits</h2>
                                <a href="result/${covoiturage.id}/details"><img src="/media/arrow-down-right-circle-fill.svg" alt="Détails"></a>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                return div;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête fetch :', error);
            covoiturage.innerHTML="";
            covoiturage.innerHTML= '<h5 class="text-center text-light">Une erreur est survenu, veuillez réessayer ulterieurement </h5>';
        });
    })
})
