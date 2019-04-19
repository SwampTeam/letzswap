<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use App\Repository\UserStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AvatarGenerator as AVA;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin", methods={"GET"})
     * @param UserRepository $userRepository
     * @param UserStatusRepository $userStatusRepository
     * @param ItemRepository $itemRepository
     * @param PictureRepository $pictureRepository
     * @param AVA $getGravar
     * @return Response
     */
    public function getUsers(UserRepository $userRepository, UserStatusRepository $userStatusRepository, ItemRepository $itemRepository, PictureRepository $pictureRepository, AVA $getGravar): Response
    {

// if ($this->isGranted('ROLE_ADMIN')) {

        //$showGravatar = $getGravar->getAvatar($email, $username, 200);
        return $this->render('Admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user_status' => $userStatusRepository->findAll(),
// 'user_avatar' => $showGravatar,
            'items' => $itemRepository->findAll(),
            'pictures' => $pictureRepository->findAll(),
            'user_role' => $this->getUser()->getRoles()
        ]);
// } else {
//  return $this->redirectToRoute('swamp');
//  }
    }
}