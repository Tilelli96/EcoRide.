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

final class LitigeController extends AbstractController
{
    #[Route('/litige', name: 'app_litige')]
    public function index(): Response
    {
        return $this->render('litige/index.html.twig', [
            'controller_name' => 'LitigeController',
        ]);
    }
}
