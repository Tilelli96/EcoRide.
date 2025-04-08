<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SearchType;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $form = $this->createForm(SearchType::class);
        return $this->render('home/index.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/mentionsLegales')]
    public function mentionsLegales(): Response
    {
        return $this->render('home/mentionsLegales.html.twig');
    }
}
