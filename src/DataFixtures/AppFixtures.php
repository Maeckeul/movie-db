<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use App\Service\Slugger;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $slugger;
        
    public function __construct(Slugger $slugger)
    {
       $this->slugger = $slugger;     
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        $categoriesList = [];
        $categories = ["Science-Fiction", "Action", "Comédie", 'Horreur', "Indé", "Animation"];
        foreach($categories as $categoryName) {

            $category = new Category();
            $category->setLabel($categoryName);
            $categoriesList[] = $category;
            $manager->persist($category);
        }

        $personsList = [];

        for($i=0; $i < 200; $i++) {

            $person = new Person();
            $person->setName($faker->name());
            $person->setBirthDate($faker->dateTimeBetween("-120 years"));
            $personsList[] = $person;
            $manager->persist($person);
        }

        for($i=0; $i < 20; $i++) {

            $movie = new Movie();
            $title = $movie->setTitle($faker->catchPhrase);
            $movie->setSlug($this->slugger->slugify($title));
            $director = $personsList[mt_rand(0, count($personsList) - 1)];
            $movie->setDirector($director);
            $movie->setReleaseDate(
                $faker->dateTimeBetween(
                    $director->getBirthDate()
                )
            );
            for($j=0; $j < mt_rand(1,3); $j++) {

                $category = $categoriesList[mt_rand(0, count($categoriesList) - 1)];
                if(!$movie->getCategories()->contains($category)) {

                    $movie->addCategory($category);
                }
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
