<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request,CovoiturageRepository $covoiturageRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $covoiturages = $covoiturageRepository->findBySearch($data);
            if((empty($covoiturages) === true)){
                $alternatives = $covoiturageRepository->FindByOtherDate($search);
                return $this->render('search/noresult.html.twig', [
                    'alternatives' => $alternatives,
                    'search' => $search,
                    'form' => $form
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
}
