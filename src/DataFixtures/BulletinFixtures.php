<?php

namespace App\DataFixtures;

use App\Entity\Bulletin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BulletinFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $bulletin = new Bulletin;
        $bulletin->setTitle('Bleu');
        $bulletin->setCategory("General");
        $bulletin->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
        //* Création de l'objet et de ses attributs

        $manager->persist($bulletin);
        //* Permet de conserver l'objet au lieu qu'il soit supprimé
        //* ObjectManager permet d'émettre une requête/demande de persistence afin d'envoyer les données dans la base de donnée

        $manager->flush();
        //* Permet d'éxécuter la requête
        //* Il est tout à fait possible de faire plusieurs requêtes avant le flush => nous serons amenés à utiliser les boucles 
    }
}