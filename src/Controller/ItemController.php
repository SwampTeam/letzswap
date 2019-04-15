<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/additem", name="add_item")
     */
    public function index()
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }
}
