<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use App\Entity\User;
use App\Repository\VoitureRepository;
use App\Repository\CovoiturageRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/covoiturage')]
final class CovoiturageController extends AbstractController
{
    #[Route('/create', name: 'create_covoiturage')]
    public function create(Request $request, EntityManagerInterface $em, VoitureRepository $vr): Response
    {
        $covoiturage = new Covoiturage();
        $user = $this->getUser();
        $voitures = $vr->findVoitureByUser($user);
        $form = $this->createForm(CovoiturageType::class, $covoiturage, array('voitures' => $voitures));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $covoiturage->setUser($user);
            $covoiturage->setStatut('à venir');
            $covoiturage->addVoyageurs($user);
            $em->persist($covoiturage);
            $em->flush($covoiturage);
            $this->addFlash('success', 'Enregistré');
            return $this->redirectToRoute("app_home");
        }
        return $this->render('covoiturage/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/historique', name: 'historique_covoiturage')]
    public function show(CovoiturageRepository $covoiturage){
        $user = $this->getUser();
        $covoiturages = $covoiturage->findByHistoricalUser($user);
        $passagerCovoiturages = $covoiturage->findByPassager($user);
        return $this->render('covoiturage/historique.html.twig', [
            'covoiturages' => $covoiturages,
            'passagerCovoiturages' => $passagerCovoiturages
        ]);
    }
}
