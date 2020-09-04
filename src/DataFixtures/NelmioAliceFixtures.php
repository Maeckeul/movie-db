<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class NelmioAliceFixtures extends Fixture
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $em)
    {
        $loader = new NativeLoader();
        
        //importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        //empile la liste d'objet à enregistrer en BDD
        foreach ($entities as $entity) {
            // $entities contient TOUTES les entités créés à partir de fixtures.yaml
            // On filtre avec ce if pour être sûr de ne calculer le slug que pour les Movie
            if ($entity instanceof Movie) {
                $entity->setSlug($this->slugger->slugify($entity->getTitle()));
            }

            $em->persist($entity);
        };
        
        //enregistre
        $em->flush();
    }
}