<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @param ItemRepository $itemRepository
     * @param PictureRepository $pictureRepository
     * @return Response
     */
    public function index(ItemRepository $itemRepository, PictureRepository $pictureRepository): Response
    {
        return $this->render('Main/index.html.twig', [
            'items' => $itemRepository->findAll(),
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/terms", name="term_of_service")
     */
    public function termsOfServicesAction()
    {
        return $this->render('/Terms/terms.html.twig', [

        ]);
    }

}
