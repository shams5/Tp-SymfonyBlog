<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagFixtures extends Fixture
{
        public function load(ObjectManager $manager)
        {
                // $product = new Product();
                // $manager->persist($product);

                for ($i = 1; $i <= 10; $i++) {
                        //* Instanciation
                        $tag = new Tag;
                        //* Définition
                        $tag->setName("Tag" . $i);
                        //* Sauvegarde
                        $manager->persist($tag);
                }
                //* Execution
                $manager->flush();
        }
}
