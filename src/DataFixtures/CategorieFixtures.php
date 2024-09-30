<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorieFixtures extends Fixture
{
    public const CATEGORIE_REFERENCE = 'categorie_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création de plusieurs catégories
        for ($i = 1; $i <= 5; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->word());  // Génère un nom de catégorie aléatoire

            // Persister la catégorie
            $manager->persist($categorie);

            // Ajouter une référence pour réutiliser cette catégorie dans d'autres fixtures
            $this->addReference(self::CATEGORIE_REFERENCE . $i, $categorie);
        }

        // Enregistrement dans la base de données
        $manager->flush();
    }
}