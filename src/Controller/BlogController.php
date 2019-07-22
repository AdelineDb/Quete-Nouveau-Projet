<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Form\CategoryType;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleSearchType;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends AbstractController
{
    /**
     * @Route({"fr": "/blog/",
     *     "en": "/blog/",
     *     "es": "/blog/"}, name="blog_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllWithCategoriesAndTagsAndAuthors();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table'
            );
        }

        $form = $this->createForm(
            ArticleSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        return $this->render('blog/index.html.twig', [
            'owner' => 'Adeline',
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $slug
     * @Route({"fr": "/blog/visualiser/{slug}/{id}",
     *     "en": "/blog/show/{slug}/{id}",
     *     "es": "/blog/visualizar/{slug}/{id}"},
     *     requirements={"slug"="[a-z0-9-]+"},
     *     name="blog_article")
     * @return Response
     */
    public function show(Article $article, int $id): Response
    {
        $category = $article->getCategory();
        $tags = $article->getTags();

        return $this->render('blog/show.html.twig',
            ['article' => $article,
                'category' => $category,
                'tags' => $tags]);

    }

    /**
     * @Route({"fr": "/blog/categorie/{name}",
     *     "en": "/blog/category/{name}",
     *     "es": "/blog/categoria/{name}"},
     *     name="blog_category", methods={"GET", "POST"})
     * @return Response A response instance
     */
    public function showByCategory(Category $category): Response
    {

        if (!$category) {
            throw $this->createNotFoundException(
                'No article with category found.'
            );
        }

        $articles = $category->getArticles();
        /*
                $articles = $this->getDoctrine()
                    ->getRepository(Article::class)
                    ->findBy(['category' => $category], ['id'=>'DESC'], 3);

                if (!$articles) {
                    throw $this->createNotFoundException(
                        'No article found in article\'s table'
                    );
                } */

        return $this->render(
            'blog/category.html.twig',
            [
                'articles' => $articles,
                'category' => $category,
            ]);

    }
}
