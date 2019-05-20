<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 20/05/2019
 * Time: 16:03
 */

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/blog/category", name="blog_categories")
     */
    public function addNewCategory(Request $request): Response
    {
                $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été ajouté');
        }

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('blog/addCategory.html.twig', [
            'category' => $category,
            //'articles' => $articles,
            'form' => $form->createView()
        ]);
    }

}