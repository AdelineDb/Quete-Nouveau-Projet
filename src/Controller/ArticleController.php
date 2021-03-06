<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Service\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route({"fr" : "/articles",
 * "en": "articles",
 *     "es": "articulos"})
 */
class ArticleController extends AbstractController
{
    /**
     * @Route({"fr": "/",
     *     "en": "/",
     *     "es": "/"}, name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {

        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllWithCategoriesAndTagsAndAuthors(),
        ]);
    }

    /**
     * @Route({"fr": "/nouveau",
     *     "en": "/new",
     *     "es": "/crear"}, name="article_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setSlug($slugify->generate($article->getTitle()));

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a bien été ajouté');

            $message = (new \Swift_Message('Un article a été ajouté'))
                ->setFrom('send@example.com')
                ->setTo('recipient@example.com')
                ->setContentType('text/html')
                ->setBody($this->renderView('article/email/notification.html.twig', ['article' => $article]));
            //faire un service mettre les info de setage ds la class et mettre dans les parentheses du send les info
            $mailer->send($message);

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);
    }

    /**
     * @Route({"fr": "/{slug}/modifier",
     *     "en": "/{slug}/edit",
     *     "es": "/{slug}/modificar"}, name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article, Slugify $slugify): Response
    {
        if ($this->getUser() === $article->getAuthor() || $this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article->setSlug($slugify->generate($article->getTitle()));
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'L\'article a bien été modifié');

                return $this->redirectToRoute('article_index', [
                    'slug' => $article->getSlug(),
                ]);
            }
        } else throw $this->createAccessDeniedException();

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('danger', 'L\'article a bien été supprimé');
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route({"fr": "/{id}/favori",
     *     "en": "/{id}/favorite",
     *     "es": "/{id}/preferido"}, name="article_favorite", methods={"GET","POST"})
     */
    public function favorite(Request $request, Article $article, ObjectManager $entityManager, User $user)
    {
        if ($this->getUser()->getFavorites()->contains($article)) {
            $this->getUser()->removeFavorite($article);
        } else {
            $this->getUser()->addFavorite($article);
        }

        $entityManager->flush();

        return $this->json([
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);
    }
}

