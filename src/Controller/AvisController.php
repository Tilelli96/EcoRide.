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
}
