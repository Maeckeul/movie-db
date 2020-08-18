<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/category")
*/
class CategoryController extends AbstractController
{

    /**
     * @Route("/{id}/view", name="category_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewCategory(Category $category)
    {

        return $this->render('category/view.html.twig', [
            'category' => $category,
        ]);
    }
}
