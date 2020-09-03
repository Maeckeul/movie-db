<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieGetPostersCommand extends Command
{
    protected static $defaultName = 'app:movie:get-posters';

    private $em;
    private $imageUploader;
    private $movieRepository;

    public function __construct(EntityManagerInterface $em, ImageUploader $imageUploader, MovieRepository $movieRepository)
    {
        parent::__construct();

        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->movieRepository = $movieRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Télécharge tous les posters de nos films depuis OMDbAPI')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Cette commande nous sert à demander, par une requête API sur OMDbAPI, les informations de nos films
        // On souhaite récupérer le lien du poster pour télécharger l'image et la placer dans notre dossier /public

        // On récupère la liste de tous les films
        // On pourrait modifier la liste pour obtenir que les films qui n'ont pas encore d'affiche
        $movies = $this->movieRepository->findAll();

        // Pour chaque film, on fait une requête sur OMDbAPI avec le titre
        // On récupère le poster, on le télécharge et on attribut le nom de ce fichier à notre $movie
        foreach ($movies as $movie) {
            // On change les espaces par %20 dans les titres pour éviter les erreurs 400
            $titleUrl = str_replace(' ', '%20', $movie->getTitle());
            // On envoie la requête et on récupère la string du JSON dans $jsonResponse
            $jsonResponse = file_get_contents('http://www.omdbapi.com/?apikey=45df58a5&t='.$titleUrl);
            // On convertit le JSON en un objet
            $objectResponse = json_decode($jsonResponse);
            
            // Si $objectResponse->Response ne contient "True" alors le film n'a pas été trouvé par OMDbAPI
            // On ne va chercher le poster que si le film a été trouvé
            // Il arrive que la propriété Poster aie pour valeur "N/A" dans la BDD ne connait pas le poster
            // (ça semble réglé aujourd'hui pour la plupart des films qui posaient problème y'a un mois)
            if ($objectResponse->Response == 'True' && $objectResponse->Poster != 'N/A') {
                
                // On peut utiliser encore file_get_contents pour récupérer le contenu de l'image
                $image = file_get_contents($objectResponse->Poster);
                
                // On utilise notre servie ImageUploader importé depuis FAQOclock
                // On s'en sert pour générer un nom de fichier au hasard
                $filename = $this->imageUploader->getRandomFileName('jpg');
                
                // On place le fichier avec e nouveau nom dans notre dossier /public/uploads
                // Le contenu du fichier est $image
                file_put_contents('public/uploads/movie_images/'.$filename, $image);

                // Maintenant que c'est fait, on modifier $movie
                $movie->setImageFilename($filename);
            }
        }

        $this->em->flush();
            
        $io = new SymfonyStyle($input, $output);
        $io->success('Toutes les affiches ont été importées');

        return 0;
    }
}