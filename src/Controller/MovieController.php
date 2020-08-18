<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}/view", name="view_movie", requirements={"id" = "\d+"}, methods={"GET"})
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
