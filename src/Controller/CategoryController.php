<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Ici on demande en parametre de notre methode de controller un objet de type Category
     * Catregory etant une entité, Doctrine va essayer d'utiliser les parametres de la route pour retrouver l'entité Category correspondant a l'id passé dans la route
     * 
     * @Route("/{id}/view", name="category_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewCategory(Category $category)
    {
        return $this->render('category/view.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/list", name="category_list", methods={"GET"})
     */
    public function listCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/add", name="category_add", methods={"GET", "POST"})
     */
    public function addCategory(Request $request)
    {
        $newCategory = new Category();

        /*
        $builder = $this->createFormBuilder($newCategory);
        $builder->add("label", TextType::class, ["label" => "Nom de la catégorie"]);
        $builder->add("submit", SubmitType::class, ["label" => "Valider"]);
        $form = $builder->getForm();
        */

        $form = $this->createForm(CategoryType::class, $newCategory);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // $data = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newCategory);
            $manager->flush();
            
            return $this->redirectToRoute('category_list');
        }

        return $this->render(
            'category/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

}
