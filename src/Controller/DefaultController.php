<?php

namespace App\Controller;


use App\Repository\ItemRepository;
use App\Repository\PictureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @param ItemRepository $itemRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(
        ItemRepository $itemRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $itemsPerPage = 4;
        return $this->render('main/index.html.twig', [
            'items' => $itemRepository->findPaginated($request, $paginator, $itemsPerPage),
        ]);
    }

    /**
     * @Route("swamp", name="swamp", methods={"GET"})
     * @return Response
     */
    public function swampAction(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', []);
    }

    /**
     * @Route("/terms", name="term_of_service")
     */
    public function termsOfServicesAction()
    {
        return $this->render('terms/terms.html.twig');
    }

}
