<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Ajouter un film
     * 
     * @Route("/add", name="movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request) 
    {
        if($request->getMethod() == Request::METHOD_POST) {
            $title = $request->request->get('title');
            if(empty($title)) {
                $this->addFlash('warning', 'Le film doit avoir un titre !');
            }

            $releaseDate = $request->request->get('releaseDate');
            if(empty($releaseDate)) {
                $this->addFlash('warning', 'Le film doit avoir une date de sortie !');
            }

            if(!empty($title) && !empty($releaseDate)) {

                $manager = $this->getDoctrine()->getManager();

                $movie = new Movie();
                $movie->setTitle($title);
                $movie->setReleaseDate(new \DateTime($releaseDate));

                $manager->persist($movie);
                $manager->flush();

                return $this->redirectToRoute('movie_add');
            }
        }

        return $this->render('movie/add.html.twig');
    }
}
