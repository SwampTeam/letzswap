<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function getUsers(UserRepository $userRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('Admin/admin.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        }

        return $this->redirectToRoute('swamp');
    }
}