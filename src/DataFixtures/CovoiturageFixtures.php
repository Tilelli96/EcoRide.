<?php

namespace App\DataFixtures;

use App\Entity\Covoiturage;
use App\Entity\User;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CovoiturageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        $lieux = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Bordeaux'];
        $statuts = ['à venir', 'en cours', 'passé'];

            for ($i = 0; $i < 10; $i++) {
                $covoiturage = new Covoiturage();
    
                $conducteur = $users[array_rand($users)];
                $voituresConducteur = $manager->getRepository(Voiture::class)->findVoitureByUser($conducteur);
                $voiture = $voituresConducteur[array_rand($voituresConducteur)];
    
                $date = new \DateTime("+{$i} days");
                $heureDepart = new \DateTime("0" . rand(7, 9) . ":00");
                $heureArrivee = (clone $heureDepart)->modify('+'.rand(2, 4).' hours');
    
                $covoiturage->setDateDepart($date);
                $covoiturage->setDateArrivee($date);
                $covoiturage->setHeureDepart($heureDepart);
                $covoiturage->setHeureArrivee($heureArrivee);
                $covoiturage->setLieuDepart($lieux[array_rand($lieux)]);
                $covoiturage->setLieuArrivee($lieux[array_rand($lieux)]);
                $covoiturage->setStatut($statuts[array_rand($statuts)]);
                $covoiturage->setNbPlaces(rand(1, 4));
                $covoiturage->setPrixPersonne(rand(10, 30));
                $covoiturage->setUser($conducteur);
                $covoiturage->setVoiture($voiture);
    
                // Ajouter 1 à 3 voyageurs différents du conducteur
                $voyageurs = array_filter($users, fn($u) => $u !== $conducteur);
                shuffle($voyageurs);
                foreach (array_slice($voyageurs, 0, rand(1, 3)) as $voyageur) {
                    $covoiturage->getVoyageurs()->add($voyageur);
                }

            $manager->persist($covoiturage);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            VoitureFixtures::class,
        ];
    }
}
