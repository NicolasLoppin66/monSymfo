<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivreFixture extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $categories = ['polar', 'sf', 'biographie', 'nouvelle', 'roman'];

        foreach ($categories as $key => $categorie) {
            $cat = new Categorie();
            $cat->setLabel($categorie);
            
            $manager->persist($cat);
            $this->addReference("cate-" . $key, $cat);
        }

        for ($u = 0; $u < 200; $u++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->lastName())
                ->setPrenom($this->faker->firstName())
                ->setDateNaissance($this->faker->dateTime('2000-01-01'))
                ->setEstPrime($this->faker->boolean(30));

            $manager->persist($auteur);
            $this->addReference('aut-' . $u, $auteur);
        }

        // creation de 50 livres
        for ($i = 0; $i < 50; $i++) {
            $livre = new Livre();
            $livre->setTitre($this->faker->sentence(7, true))
                ->setResume($this->faker->paragraph(5, true))
                ->setCategorie($this->getReference('cate-' . rand(0, count($categories) - 1)))
                ->setEditeur($this->faker->word())
                ->setDateParution(new \DateTime($this->faker->date('Y-m-d', 'now')));

            for ($z = 0; $z < rand(1, 3); $z++) {
                $test = rand(0, 199);
                $livre->addAuteur($this->getReference('aut-' . $test));
            }
            $manager->persist($livre);
        }
        $manager->flush();
    }
}