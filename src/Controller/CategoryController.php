<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\CategoryType;
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

            $this->addFlash("success", "La Catégorie " . $newCategory->getLabel() . " a été créee !");
            
            return $this->redirectToRoute('category_list', ['id' => $newCategory->getId()]);
        }

        return $this->render(
            'category/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/update", name="category_update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function updateCategory(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // $data = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "La Catégorie " . $category->getLabel() . " a été modifiée !");
            
            return $this->redirectToRoute('category_view', ['id' => $category->getId()]);
        }

        return $this->render(
            'category/update.html.twig',
            [
                "category" => $category,
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="category_delete", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function deleteCategory(Category $category) 
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        $this->addFlash("danger", "La Catégorie " . $category->getLabel() . " a été supprimée !");
            
        return $this->redirectToRoute('category_list');
    }
}
