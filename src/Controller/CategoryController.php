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
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route({"fr": "/categorie",
 *     "en": "/category",
 *     "es": "/categoria"}, name="category_")
 */

class CategoryController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'category' => $categoryRepository->findAll(),
        ]);
    }


    /**
     * @Route({"fr": "/nouveau",
     *     "en": "/new",
     *     "es": "/crear"}, name="add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
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

        return $this->render('blog/new.html.twig', [
            'category' => $category,
            //'articles' => $articles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @Route({"fr": "/modifier/{id}",
     *     "en": "/edit/{id}",
     *     "es": "/modificar/{id}"}, name="edit", methods={"GET", "POST"})
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'article a bien été modifié');

            return $this->redirectToRoute('category_index');
        } //else throw $this->createAccessDeniedException();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()]);
    }
    /**
     * @Route({"fr": "/supprimer/{id}",
     *     "en": "/delete/{id}",
     *     "es": "/eliminar/{id}"}, name="delete", methods={"GET", "POST"})
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
