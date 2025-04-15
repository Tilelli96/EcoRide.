<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SearchType;
use App\Document\Preferences;
use App\Form\PreferencesType;
use Doctrine\ODM\MongoDB\DocumentManager;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DocumentManager $dm): Response
    {
        $form = $this->createForm(SearchType::class);
        $user = $this->getUser();

        if($user){
            $preferences = $dm->getRepository(Preferences::class)->findOneBy(['userId' => $user->getId()]) ?? null;
        }else{
            $preferences = null;
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'preferences' => $preferences
        ]);
    }
    #[Route('/mentionsLegales')]
    public function mentionsLegales(): Response
    {
        return $this->render('home/mentionsLegales.html.twig');
    }
}
