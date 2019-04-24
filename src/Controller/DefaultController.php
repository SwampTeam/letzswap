<?php

namespace App\Controller;

use App\Repository\ItemRepository;
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
//        $roles = empty($this->getUser()->getRoles()) ? $this->getUser()->getRoles() : '';
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $roles = $this->getUser()->getRoles();
        } else {
            $roles = '';
        }
        return $this->render('main/index.html.twig', [
            'items' => $itemRepository->findPaginated($request, $paginator, $itemsPerPage),
            'uRoles' => $roles
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
        return $this->render('terms/terms.html.twig', ['uRoles' => $this->getUser()->getRoles()]);
    }

}
