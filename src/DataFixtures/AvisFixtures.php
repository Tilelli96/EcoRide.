<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $statuts = ['validé', 'à confirmer'];

        foreach ($users as $index => $user) {
            // Liste des autres utilisateurs pour le champ created_by
            $otherUsers = array_filter($users, fn($u) => $u !== $user);
            $otherUsers = array_values($otherUsers); // pour un accès par index

            for ($i = 0; $i < 5; $i++) {
                $avis = new Avis();
                $avis->setCommentaire("Ceci est un avis numéro $i pour l'utilisateur ".$user->getEmail());
                $avis->setNote(rand(1, 5));
                $avis->setStatut($statuts[$i % count($statuts)]);
                $avis->setUser($user);
                $avis->setCreatedBy($otherUsers[$i % count($otherUsers)]);

                $manager->persist($avis);
            }
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
