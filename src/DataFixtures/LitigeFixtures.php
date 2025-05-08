<?php

namespace App\DataFixtures;

use App\Entity\Litige;
use App\Entity\User;
use App\Entity\Covoiturage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LitigeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $covoiturages = $manager->getRepository(Covoiturage::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $litige = new Litige();

            $covoiturage = $covoiturages[array_rand($covoiturages)];
            $voyageurs = $covoiturage->getVoyageurs()->toArray();
            $user = $voyageurs[array_rand($voyageurs)];

            $litige->addUser($user);
            $litige->addCovoiturage($covoiturage);

            $manager->persist($litige);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CovoiturageFixtures::class,
        ];
    }
}
