<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{

    /**
     * @Route("/list", name="movie_list", methods={"GET"})
     */
    public function list(Request $request) {

        $search = $request->query->get('search', "");

        /*
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findBy(
            [],
            ["title" => "asc"]
        );
        */

        $movies = $this->getDoctrine()->getRepository(Movie::class)->findByPartialTitle($search);

        return $this->render('movie/list.html.twig', [
            "movies" => $movies,
            "search" => $search
        ]);
    }

    /**
     * @Route("/{id}/view", name="movie_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function view($id)
    {
        // Pas besoin car on utilise le paramConverter de Doctrine
        // il s'occupe de recuperer mon entité grace aux parametres de la route
        $movie = $this->getDoctrine()->getRepository(Movie::class)->findWithFullData($id);

        if(!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        return $this->render('movie/view.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/add", name="movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request) 
    {
        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
            
        if($form->isSubmitted() && $form->isValid()) {

            /**
             * @var UploadedFile $imageFile 
             */
            $imageFile = $form->get('imageFile')->getData();
            if($imageFile) {

                $fileName = uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('movie_image_directory'),
                    $fileName
                );

                $movie->setImageFilename($fileName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movie_view', ['id' => $movie->getId()]);
        }

        return $this->render(
            'movie/add.html.twig', 
            [
                "movie" => $movie,
                "form" => $form->createView()
            ]
        );
    }

    
    /**
     * @Route("/{id}/delete", name="movie_delete", methods={"GET"})
     */
    public function delete(Movie $movie) 
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($movie);
        $manager->flush();

        $this->addFlash("danger", "Le Film " . $movie->getTitle() . " a été supprimé !");
            
        return $this->redirectToRoute('movie_list');
    }

    
    /**
     * @Route("/{id}/update", name="movie_update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function update(Movie $movie, Request $request)
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
            
        if($form->isSubmitted() && $form->isValid()) {
                
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "Le Film " . $movie->getTitle() . " a été modifié !");

            return $this->redirectToRoute('movie_view', ['id' => $movie->getId()]);
        }

        return $this->render(
            'movie/update.html.twig', 
            [
                "movie" => $movie,
                "form" => $form->createView()
            ]
        );
    }
}
