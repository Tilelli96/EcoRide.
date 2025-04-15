<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LitigeRepository;
use App\Entity\Litige;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;
use App\Entity\Covoiturage;
use App\Repository\UserRepository;
use App\Entity\User;

#[Route('/employer/litiges')]
final class LitigeController extends AbstractController
{
    #[Route('/index', name: 'app_litige')]
    public function index(LitigeRepository $litige): Response
    {
        $litiges = $litige->findAll();
        return $this->render('litige/index.html.twig', [
            'litiges' => $litiges,
        ]);
    }

    #[Route('/{id}/annuler')]
    public function cancel(EntityManagerInterface $em, Litige $litige): Response
    {
        $em->remove($litige);
        $em->flush();
        $this->addFlash('success', 'le litige a bien été supprimé');
        return $this->redirectToRoute('app_employer');
    }

    #[Route('/{id}/valider')]
    public function validate(EntitymanagerInterface $em, Litige $litige): Response
    {
        $litige->getUser()->setCredit($litige->getUser()->getCredit() - $litige->getCovoiturage()->getPrixPersonne());
        $litige->getCovoiturage()->getUser()->setCredit($litige->getCovoiturage()->getUser()->getCredit() + ($litige->getCovoiturage()->getPrixPersonne() - 2));
        $em->remove($litige);
        $em->flush();
        $this->addFlash('success', 'Votre validation a bien été enregistrée');
        return $this->redirectToRoute('app_employer');
    }
}
