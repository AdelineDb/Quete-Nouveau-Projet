<?php

namespace App\Controller;

use App\Form\ArticleSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/",
     *     "en": "/",
     *     "es": "/"
     * }, name="app_index")
     */
    public function index()
    {
        return $this->render('default.html.twig');

    }

    public function dispatch(Request $request)
    {
        $locale = $request->getLocale();

        return $this->redirectToRoute('app_index', ['_locale'=> $locale]);
    }
}