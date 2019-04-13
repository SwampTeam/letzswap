<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('Main/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function termsOfServicesAction()
    {
        return new Response ('<!DOCTYPE> 
        <html> <body> This are the terms ...</html> </body>');
    }
}
