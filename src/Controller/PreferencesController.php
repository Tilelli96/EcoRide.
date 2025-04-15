<?php

namespace App\Controller;

use App\Document\Preferences;
use App\Form\PreferencesType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreferencesController extends AbstractController
{
    #[Route('/preferences/add/{Id}', name: 'add_preferences')]
    public function addPreferences(Request $request, DocumentManager $dm, string $Id): Response
    {
        $Preferences = $dm->getRepository(Preferences::class)->findOneBy(['userId' => $this->getUser()->getId()]) ?? new Preferences();
        $form = $this->createForm(PreferencesType::class, $Preferences);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $autresPreferences = $form->get('autresPreferences')->getData() ?? null;
            $Preferences->addAutrePreference($autresPreferences);
            $Preferences->setUserId($this->getUser()->getId()); 
            $dm->persist($Preferences);
            $dm->flush();
            $this->addFlash('success', 'Votre validation a bien été enregistrée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/preferences.html.twig', [
            'form' => $form,
        ]);
    }

}
