<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * Vue de la liste des films
     * 
     * @Route("/list", name="movies_list", methods={"GET"})
     */
    public function listMovies()
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();

        return $this->render('movie/list.html.twig', [
            "movies" =>$movies
        ]);
    }

    /**
     * Vue d'un film
     * 
     * @Route("/{id}/view", name="movie_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewMovie($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id); 

        if(!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        return $this->render('movie/view.html.twig', [
            "movie" => $movie
        ]);
    }
}
