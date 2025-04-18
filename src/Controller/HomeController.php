<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DocumentManager $dm): Response
    {
        return $this->render('home/index.html.twig');
    }
}
