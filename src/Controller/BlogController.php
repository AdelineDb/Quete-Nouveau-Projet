<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     */
    public function index()
    {
       /* return new Response(
            '<html><body>Blog Index</body></html>'
        ); */

        return $this->render('blog/index.html.twig', [
            'owner' => 'Adeline',
            ]);
    }

    /**
     * @Route("/blog/show/{slug}", requirements={"slug"="[a-z0-9-]+"}, defaults={"slug"="Article Sans Titre"}, name="blog_article")
     */
    public function show($slug)
    {

        $slug = str_replace('-', ' ', $slug);
        $slug = ucfirst($slug);

        return $this->render('blog/show.html.twig', ['slug' =>$slug]);
    }
}
