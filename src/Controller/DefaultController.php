<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        // Redirect to the ItemController and the method getItems to render the view with the items
        return $this->forward('App\Controller\ItemController::getItems');
    }

    /**
     * @Route("swamp", name="swamp", methods={"GET"})
     * @return Response
     */
    public function swampAction() : Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
    }

    /**
     * @Route("/terms", name="term_of_service")
     */
    public function termsOfServicesAction()
    {
        return $this->render('/Terms/terms.html.twig');
    }

}
