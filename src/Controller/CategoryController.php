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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategoryController extends AbstractController
{
    /**
     * @Route("/blog/category", name="blog_categories")
     * @IsGranted("ROLE_ADMIN")
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

            $this->addFlash('success', 'La catégorie a été ajoutée');
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

    /**
     * @param Request $request
     * @param Category $category
     * @Route ("/blog/category/edit/{id}", name="blog_category_edit", methods={"GET", "POST"})
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */

    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'article a bien été modifié');

            return $this->redirectToRoute('blog_category');
        } else throw $this->createAccessDeniedException();

        return $this->render('blog/editCategory.html.twig', [
            'category' => $category,
            'form' => $form->createView()]);

    }
    /**
     * @Route("/blog/category/delete/{id}", name="blog_category_delete", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('danger', 'La catégorie a bien été supprimée');
        }

        return $this->redirectToRoute('blog_category');
    }
}
