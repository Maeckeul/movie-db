<?php

namespace App\Controller\Api\V1;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/v1/movies", name="api_v1_movies")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository, ObjectNormalizer $objectNormalizer)
    {
        $movies = $movieRepository->findAll();

        $serializer = new Serializer([$objectNormalizer]);

        $json = $serializer->normalize($movies, null, ['groups' => 'api_v1_movies']);

        return $this->json($json);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Movie $movie, ObjectNormalizer $objectNormalizer)
    {
        $serializer = new Serializer([$objectNormalizer]);

        $json = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

        return $this->json($json);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     */
    public function new(Request $request, ObjectNormalizer $objectNormalizer)
    {
        $movie = new Movie();
        
        $form = $this->createForm(MovieType::class, $movie);
        
        $json = json_decode($request->getContent(), true);

        $form->submit($json);

        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $serializer = new Serializer([$objectNormalizer]);
        
            $movieJson = $serializer->normalize($movie, null, ['groups' => 'api_v1_movies']);

            return $this->json($movieJson, 201);
        } else {

            return $this->json($form->getErrors(true), 400);
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     */
    public function update()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/V1/MovieController.php',
        ]);
    }
}
