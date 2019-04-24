<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\StatusRepository;
use App\Repository\UserStatusRepository;
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
     * @param StatusRepository $statusRepository
     * @param UserStatusRepository $userStatusRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(
        ItemRepository $itemRepository,
        StatusRepository $statusRepository,
        UserStatusRepository $userStatusRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $itemsPerPage = 4;

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $status = $statusRepository->findOneByLabel('banned');
            $banFind = $userStatusRepository->findOneBy([
                'users' => $user,
                'statuses' => $status
            ]);

            if ($banFind) {
                $this->addFlash('danger', "You are banned from our website!");
                return $this->render('main/index.html.twig', [
                    'items' => $itemRepository->findPaginated($request, $paginator, $itemsPerPage),
                    'uRoles' => 'Banned'
                ]);
            }
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
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }

    /**
     * @Route("denied", name="denied", methods={"GET"})
     * @return Response
     */
    public function deniedAction(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }

    /**
     * @Route("/terms", name="term_of_service")
     */
    public function termsOfServicesAction()
    {
        return $this->render('terms/terms.html.twig');
    }

}
