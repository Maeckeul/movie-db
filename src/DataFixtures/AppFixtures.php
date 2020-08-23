<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = ["Science-Fiction", "Action", "Comédie"];
        foreach($categories as $categoryName) {

            $category = new Category();
            $category->setLabel($categoryName);
            $manager->persist($category);
        }

        for($i=0; $i < 200; $i++) {
            $person = new Person();
            $person->setName("Person" . $i);
            $person->setBirthDate(new \DateTime("1972-01-01"));
            $manager->persist($person);
        }

        $manager->flush();
    }
}
