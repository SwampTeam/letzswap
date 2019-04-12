<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function termsOfServicesAction()
    {
        return new Response ('<!DOCTYPE> 
        <html> <body> This are the terms ...</html> </body>');
    }
}
