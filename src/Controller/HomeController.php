<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return new Response('<h1>MERDEUH!!!</h1>');
    }
}
