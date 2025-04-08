<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;
use App\Entity\Covoiturage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request,CovoiturageRepository $covoiturageRepository, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $data = $request->query->all();
        if(!empty($data)){
            $covoiturages = $covoiturageRepository->findBySearch($data);
            $covoituragesJson = $serializer->serialize($covoiturages, 'json', ['groups' => 'covoiturage:read']);
            $filtresHtml = $this->renderView('search/filtres.html.twig');
           if((empty($covoiturages) === true)){
                $alternatives = $covoiturageRepository->FindByOtherDate($data);
                $alternativesJson = $serializer->serialize($alternatives, 'json', ['groups' => 'covoiturage:read']);
                return new JsonResponse ([
                    'alternatives' => json_decode($alternativesJson),
                    'covoiturages' => [],
                    'filtresHtml' => $filtresHtml
                ]);
            } else {
                return new JsonResponse ([
                    'covoiturages' => json_decode($covoituragesJson),
                    'alternatives' => [],
                    'filtresHtml' => $filtresHtml
                ]);
            }
            
        }
        return $this->render('search/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('result/{id}/details', name: 'app_details')]
    public function details(Covoiturage $covoiturage){
        
        return $this->render('/covoiturage/details.html.twig', [
            'covoiturage' => $covoiturage
        ]);
    }
}
