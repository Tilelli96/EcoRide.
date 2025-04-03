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

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request,CovoiturageRepository $covoiturageRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $covoiturages = $covoiturageRepository->findBySearch($data);
            if((empty($covoiturages) === true)){
                $alternatives = $covoiturageRepository->FindByOtherDate($data);
                return $this->render('search/noresult.html.twig', [
                    'form' => $form,
                    'alternatives' => $alternatives
                ]);
            } else {
                return $this->render('search/result.html.twig', [
                    'covoiturages' => $covoiturages,
                    'form' => $form
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
