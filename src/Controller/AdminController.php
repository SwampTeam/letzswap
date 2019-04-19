<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AvatarGenerator as AVA;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin", methods={"GET"})
     * @param UserRepository $userRepository
     * @param ItemRepository $itemRepository
     * @param PictureRepository $pictureRepository
     * @param AVA $getGravar
     * @return Response
     */
    public function getAdmin(UserRepository $userRepository, ItemRepository $itemRepository, PictureRepository $pictureRepository, AVA $getGravar): Response
    {
//        $itemStatus = new ItemStatus();
//        $itemStatus->setItem($item)->setStatus($status);
//        $entityManager->persist($itemStatus);
// if ($this->isGranted('ROLE_ADMIN')) {
        return $this->render('Admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user_avatar' => $getGravar,
            'items' => $itemRepository->findAll(),
            'pictures' => $pictureRepository->findAll(),
            'user_role' => $this->getUser()->getRoles()
        ]);
// } else {
//  return $this->redirectToRoute('swamp');
//  }
    }
}