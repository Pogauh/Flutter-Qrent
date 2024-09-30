<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de plusieurs utilisateurs pour les tests
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setNom("Nom $i");
            $user->setPrenom("Prenom $i");
            $user->setEmail("user$i@example.com");
            $user->setRoles(['ROLE_USER']);

            // Hashage du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            // Enregistrement de l'utilisateur
            $manager->persist($user);

        }

        $manager->flush();
    }
}