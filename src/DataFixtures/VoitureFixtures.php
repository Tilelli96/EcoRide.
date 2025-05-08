<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VoitureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $couleur = ['bleu', 'rouge', 'vert'];
        $energie = ['Voitures Électriques',
                    'Voitures Thermiques (Essence)',
                    'Voitures Thermiques (Diesel)',
                    'Voitures HEV et PHEV (Hybride)',
                    'E85 (Bioéthanol)',
                    'Gaz Naturel (GNV) et GPL (Gaz de Pétrole Liquéfié)',
                    'Hydrogène (Voitures à Pile à Combustible - FCEV)]'
                    ];

        foreach ($users as $user) {
            // Création d'une voiture pour chaque utilisateur
            $voiture = new Voiture();
            $voiture->setMarque('Marque'.$user->getId());
            $voiture->setModel('Modèle'.$user->getId());
            $voiture->setImmatriculation('ABC-000-'.$user->getId());
            $voiture->setCouleur($couleur[rand(0, count($couleur) - 1)]);
            $voiture->setEnergie($energie[rand(0, count($energie) - 1)]);
            $voiture->setUser($user);
            $manager->persist($voiture);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
