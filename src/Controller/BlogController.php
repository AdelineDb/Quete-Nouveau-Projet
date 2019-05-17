<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function index(): Response
    {
        /* return new Response(
             '<html><body>Blog Index</body></html>'
         ); */

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table'
            );
        }
        foreach ($articles as $article) {
            $article->url = preg_replace('/ /', '-', strtolower($article->getTitle()));
        }

        return $this->render('blog/index.html.twig', [
            'owner' => 'Adeline',
            'articles' => $articles
        ]);
    }

    /**
     * @param string $slug
     * @Route("/blog/show/{slug}",
     *     requirements={"slug"="[a-z0-9-]+"},
     *     name="blog_article")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No Slug');
        }

        $slug = str_replace('-', ' ', ucwords($slug));

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with ' . $slug . ' title, found in article\'s table.'
            );
        }
        $category = $article->getCategory();

        return $this->render('blog/show.html.twig',
            ['article' => $article,
                'slug' => $slug,
                'category' => $category]);
    }

    /**
     * @param string $categoryName The slugger
     *
     * @Route("/blog/category/{categoryName}",
     *     name="blog_category")
     * @return Response A response instance
     */
    public function showByCategory(Category $categoryName): Response
    {
        if (!$category) {
            throw $this->createNotFoundException(
                'No article with ' . $categoryName . ' category, found.'
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

        foreach ($articles as $article) {
            $article->url = preg_replace('/ /', '-', strtolower($article->getTitle()));
        }


        return $this->render(
            'blog/category.html.twig',
            [
                'articles' => $articles,
                'category' => $category,
            ]);

    }
}
