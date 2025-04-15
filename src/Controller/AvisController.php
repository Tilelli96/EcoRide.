<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Covoiturage;
use App\Entity\User;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    #[Route('/{id}/index', name: 'avis_index')]
    public function index(EntityManagerInterface $em, AvisRepository $avisRepository, User $user): Response
    {
        $avis = $avisRepository->findByUser($user);
        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/{id}/create', name: 'app_avis')]
    public function create(Request $Request, EntityManagerInterface $em, User $user ): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($Request);
        if($form->isSubmitted() && $form->isValid()){
            $avis->setStatut('à confirmer');
            $avis->setCreatedBy($this->getUser());
            $avis->setUser($user);
            if(empty($user->getNote())){
                $user->setNote($avis->getNote());
            } else {
                $user->setNote(($avis->getNote() + $user->getNote()) / 2);
            }
            $em->persist($avis);
            $em->flush($avis);
            $this->addFlash('success', 'Votre validation a bien été enregistrée');
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('avis/create.html.twig', [
            'form' => $form,
        ]);
    }
}
