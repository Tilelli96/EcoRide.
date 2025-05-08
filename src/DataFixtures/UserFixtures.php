<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user$i@ecoride.com");
            $user->setNom("Nom$i");
            $user->setPrenom("Prenom$i");
            $user->setTelephone("060000000$i");
            $user->setAdresse("10$i Rue de Paris");
            $user->setDateNaissance(new \DateTime('1990-01-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $user->setPseudo("User$i");
            $user->setNote(mt_rand(1, 5));
            $user->setCredit(100);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);

            // Mot de passe non hashé pour test (à ne pas faire pour un vrai utilisateur)
            $user->setPassword("password$i");

            $manager->persist($user);
        }

        for ($i = 1; $i <= 3; $i++) {
            $employe = new User();
            $employe->setEmail("employe$i@ecoride.com");
            $employe->setNom("Nom$i");
            $employe->setPrenom("Prenom$i");
            $employe->setTelephone("060000000$i");
            $employe->setAdresse("10$i Rue des ouvriers");
            $employe->setDateNaissance(new \DateTime('1990-01-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $employe->setPseudo("employe$i");
            $employe->setNote(mt_rand(1, 5));
            $employe->setCredit(0);
            $employe->setRoles(['ROLE_EMPLOYE']);
            $employe->setIsVerified(true);

            // Mot de passe non hashé pour test (à ne pas faire pour un vrai utilisateur)
            $employe->setPassword("password+$i+employe");

            $manager->persist($employe);
        }

        $adminEmail = 'admin@ecoride.com';
        $existingAdmin = $manager->getRepository(User::class)->findOneBy(['email' => $adminEmail]);

        if (!$existingAdmin) {
            $admin = new User();
            $admin->setEmail($adminEmail);
            $admin->setNom("Admin");
            $admin->setPrenom("Admin");
            $admin->setTelephone("0606060606");
            $admin->setAdresse("1 Rue des Admins");
            $admin->setDateNaissance(new \DateTime('1980-01-01'));
            $admin->setPseudo("admin");
            $admin->setNote(0);
            $admin->setCredit(0);
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setIsVerified(true);

            // Mot de passe provisoire pour test
            $admin->setPassword("adminpass");

            $manager->persist($admin);
        }

        $manager->flush();
    }
}
