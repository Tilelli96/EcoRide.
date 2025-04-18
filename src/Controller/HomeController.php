<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $html = file_get_contents(__DIR__ . '/../../public/index.html');
        return new Response($html);
    }
}
