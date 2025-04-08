<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ARepository;
use App\Entity\A;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/employer')]
final class EmployerController extends AbstractController
{
    #[Route('/', name: 'app_employer')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('employer/index.html.twig', [
            'user' => $user,
        ]);
    }
}
