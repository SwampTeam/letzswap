<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserStatus;
use App\Repository\ItemRepository;
use App\Repository\PictureRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Repository\UserStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AvatarGenerator as AVA;

class AdminController extends AbstractController
{

    /**
     * @Route("/sw-admin", name="admin", methods={"GET"})
     * @param UserRepository $userRepository
     * @param ItemRepository $itemRepository
     * @param PictureRepository $pictureRepository
     * @param AVA $getGravar
     * @return Response
     */
    public function getAdmin(UserRepository $userRepository, ItemRepository $itemRepository, PictureRepository $pictureRepository, AVA $getGravar): Response
    {
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

    /**
     * @Route("/sw-admin/delete_user/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     */
    public function deleteUser(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete-user', $request->request->get('csrf_token_rmuser'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $userRepository->findOneById($user->getId());
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/sw-admin/ban_user/{id}", name="user_ban", methods={"POST"})
     * @param User $user
     * @return Response
     */
    public function banUser(User $user): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $userRepository = $manager->getRepository(User::class);
        $userToban = $userRepository->findOneById($user);

        if (!$userToban) {
            throw new NotFoundHttpException('User not found');
        }

        $status = $manager->getRepository(Status::class)->findOneByLabel('banned');

        if (!$status) {
            $status = new Status();
            $status->setLabel('banned');
            $manager->persist($status);
        }

        $userStatus = new UserStatus();
        $userStatus->setUser($user)->setStatus($status);
        $manager->persist($userStatus);
        $manager->flush();
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/sw-admin/unban_user/{id}", name="user_unban", methods={"POST"})
     * @param User $user
     * @param UserStatusRepository $userStatusRepository
     * @param StatusRepository $statusRepository
     * @return Response
     */
    public function unbanUser(User $user, UserStatusRepository $userStatusRepository, StatusRepository $statusRepository): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $status = $statusRepository->findOneByLabel('banned');
        $repo = $userStatusRepository->findOneByStatuses($status);

        $manager->remove($repo);
        $manager->flush();

        return $this->redirectToRoute('admin');
    }

}