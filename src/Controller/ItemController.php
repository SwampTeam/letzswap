<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Status;
use App\Entity\ItemStatus;
use App\Entity\Picture;
use App\Form\ItemType;
use App\Form\SwapFormType;
use App\Form\ReportFormType;
use App\Mailer\Mailer;
use App\Repository\ItemStatusRepository;
use App\Repository\PictureRepository;
use App\Repository\StatusRepository;
use App\Repository\UserStatusRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{

    /**
     * @Route("/picture/{picture}", name="get_picture_content")
     * @param Picture $picture
     * @return Response
     */
    public function getPicture(Picture $picture)
    {
        $path = $this->getParameter('upload_directory') . $picture->getPath();
        return new Response(
            file_get_contents($path),
            200,
            ['Content-Type' => $picture->getMimeType()]
        );
    }


    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @param Request $request
     * @param StatusRepository $statusRepository
     * @param UserStatusRepository $userStatusRepository
     * @return Response
     * @throws \Exception
     */
    public function addItem(Request $request, StatusRepository $statusRepository, UserStatusRepository $userStatusRepository): Response
    {
        // Check if not activated
        if (!$this->isGranted('ROLE_USER')) {
            // Redirect to 403 if not user activated
            return $this->redirectToRoute('denied');
        }

        // Check if banned
        $user = $this->getUser();
        $status = $statusRepository->findOneByLabel('banned');
        $banFind = $userStatusRepository->findOneBy([
            'users' => $user,
            'statuses' => $status
        ]);

        if ($banFind) {
            // Redirect to 403 if  user banned
            return $this->redirectToRoute('denied');
        }
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the user who posted the item
            $item->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);

            // Picture processing
            $file = $form->get('picture')->getData();
            $ext = $file->guessExtension();
            $fileName = Uuid::uuid4()->toString() . '.' . $ext;
            $picture = new Picture();
            $picture->setPath($fileName);
            $picture->setMimeType($file->getMimeType());
            $picture->setItem($item);
            $file->move($this->getParameter('upload_directory'), $fileName);
            $entityManager->persist($picture);

            // Set active status
            $status = $entityManager->getRepository(Status::class)->findOneByLabel('active');
            if (!$status) {
                $status = new Status();
                $status->setLabel('active');
                $entityManager->persist($status);
            }
            $itemStatus = new ItemStatus();
            $itemStatus->setItem($item)->setStatus($status);
            $entityManager->persist($itemStatus);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $roles = $this->getUser()->getRoles();
        } else {
            $roles = '';
        }
        return $this->render('item/new.html.twig', [
            'item' => $item,
            'uRoles' => $roles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_details", methods={"GET"})
     * @param Request $request
     * @param Item $item
     * @param PictureRepository $pictureRepository
     * @param Mailer $mailer
     * @return Response
     */
    public
    function getDetails(
        Request $request, Item $item, PictureRepository $pictureRepository,
        Mailer $mailer
    ): Response
    {
        $swapForm = $this->createForm(
            SwapFormType::class,
            null,
            ['standalone' => true, 'method' => 'GET']
        );

        $swapForm->handleRequest($request);
        if ($swapForm->isSubmitted() && $swapForm->isValid()) {

            $user = $this->getUser();
            $swapFormData = $swapForm->getData();
            $mailer->sendSwapMail($user, $item, $swapFormData);
            $this->addFlash('success', "We just sent an email to the owner informing you are interested.");

            return $this->redirectToRoute('homepage');
        }

        $reportForm = $this->createForm(
            ReportFormType::class,
            null,
            ['standalone' => true, 'method' => 'GET']
        );

        $reportForm->handleRequest($request);
        if ($reportForm->isSubmitted() && $reportForm->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $status = $manager->getRepository(Status::class)->findOneByLabel('reported');
            if (!$status) {
                $status = new Status();
                $status->setLabel('reported');
                $manager->persist($status);
            }

            $itemStatus = new ItemStatus();
            $itemStatus->setItem($item)
                ->setStatus($status);
            $manager->persist($itemStatus);

            $manager->flush();

            $user = $this->getUser();

            $reportFormData = $reportForm->getData();
            $mailer->sendReportMail($user, $item, $reportFormData);

            $this->addFlash('success', "The item was successfully reported and we send an email to its owner.");

            return $this->redirectToRoute('homepage');
        }

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $roles = $this->getUser()->getRoles();
        } else {
            $roles = '';
        }
        $email = $item->getUser()->getEmail();
        $username = $item->getUser()->getUsername();
        return $this->render('item/details.html.twig', [
            'item' => $item,
            'picture' => $pictureRepository->findOneByItem($item->getId()),
            'username' => $username,
            'uRoles' => $roles,
            'swapForm' => $swapForm->createView(),
            'reportForm' => $reportForm->createView(),
        ]);
    }

    public
    function rmFile($file)
    {
        $file_path = 'var/uploads' . $file;
        if (file_exists($file_path)) {
            chown($file_path, 465);
            unlink($file_path);
        }
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Item $item
     * @param PictureRepository $pictureRepository
     * @return Response
     * @throws \Exception
     */
    public
    function editItem(Request $request, Item $item, PictureRepository $pictureRepository): Response
    {
        // Owner of the Item check
        if ($item->getUser() !== $this->getUser()) {
            //Redirect to 404 if not owner
            return $this->redirectToRoute('swamp');
        }

        $form = $this->createForm(ItemType::class, $item, ['empty_data' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $form->get('picture')->getData();
            if (!empty($file)) {
                $oldPic = $item->getPictures();
                $this->rmFile($oldPic['name']);
                $picture = $pictureRepository->findOneByItem($item->getId());
                $em->remove($picture);
                unset($picture);
                $ext = $file->guessExtension();
                $fileName = Uuid::uuid4()->toString() . '.' . $ext;
                $picture = new Picture();
                $picture->setPath($fileName);
                $picture->setMimeType($file->getMimeType());
                $picture->setItem($item);
                $file->move($this->getParameter('upload_directory'), $fileName);
                $em->persist($picture);
                $em->flush();
            } else {
                $this->getDoctrine()
                    ->getManager()
                    ->flush();
            }
            return $this->redirectToRoute('item_details', [
                'id' => $item->getId(),
            ]);
        }
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $roles = $this->getUser()->getRoles();
        } else {
            $roles = '';
        }
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'uRoles' => $roles,
            'picture' => $item->getPictures(),
            'form' => $form->createView(),
            'upload_directory' => $this->getParameter('upload_directory')
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     * @param Request $request
     * @param Item $item
     * @param PictureRepository $pictureRepository
     * @param ItemStatusRepository $itemStatusRepository
     * @return Response
     */
    public
    function deleteItem(Request $request, Item $item, PictureRepository $pictureRepository, ItemStatusRepository $itemStatusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete-item', $request->request->get('csrf_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $picture = $pictureRepository->findOneByItem($item->getId());
            $status = $itemStatusRepository->findOneByItems($item->getId());
            $entityManager->remove($status);
            $entityManager->remove($item);
            $entityManager->remove($picture);
            $entityManager->flush();
        }
        return $this->redirectToRoute('homepage');
    }

}