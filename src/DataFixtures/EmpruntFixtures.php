<?php

namespace App\DataFixtures;

use App\Entity\Emprunt;
use App\Entity\User;
use App\Entity\Materiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmpruntFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Récupération des utilisateurs et matériels existants
        $users = $manager->getRepository(User::class)->findAll();
        $materiels = $manager->getRepository(Materiel::class)->findAll();

        // Création de plusieurs emprunts
        for ($i = 1; $i <= 10; $i++) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($faker->dateTimeBetween('-1 years', 'now'));
            $emprunt->setDateRetour($faker->dateTimeBetween('now', '+1 years'));
            $emprunt->setStatut($faker->boolean());

            // Associer un utilisateur aléatoire
            $user = $faker->randomElement($users);
            $emprunt->setUser($user);

            // Associer un matériel aléatoire
            $materiel = $faker->randomElement($materiels);
            $emprunt->setMateriel($materiel);

            // Persister l'emprunt
            $manager->persist($emprunt);
        }

        // Enregistrement dans la base de données
        $manager->flush();
    }
}