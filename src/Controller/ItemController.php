<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Status;
use App\Entity\ItemStatus;
use App\Entity\Picture;
use App\Form\ItemType;
use App\Mailer\Mailer;
use App\Service\AvatarGenerator as AVA;
use App\Repository\ItemRepository;
use App\Repository\ItemStatusRepository;
use App\Repository\PictureRepository;
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
     * @return Response
     * @throws \Exception
     */
    public function addItem(Request $request): Response
    {
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
        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_details", methods={"GET"})
     * @param Item $item
     * @param PictureRepository $pictureRepository
     * @param AVA $getGravar
     * @return Response
     */
    public function getDetails(Item $item, PictureRepository $pictureRepository, AVA $getGravar): Response
    {
        $email = $item->getUser()->getEmail();
        $username = $item->getUser()->getUsername();
        $showGravatar = $getGravar->getAvatar($email, $username, 200);
        return $this->render('item/details.html.twig', [
            'item' => $item,
            'picture' => $pictureRepository->findOneByItem($item->getId()),
            'avatar' => $showGravatar,
            'username' => $username
        ]);
    }

    public function rmFile($file)
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
    public function editItem(Request $request, Item $item, PictureRepository $pictureRepository): Response
    {
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
        return $this->render('item/edit.html.twig', [
            'item' => $item,
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
    public function deleteItem(Request $request, Item $item, PictureRepository $pictureRepository, ItemStatusRepository $itemStatusRepository): Response
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
        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/{id}/report", name="item_report", methods={"GET"})
     * @param Item $item
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reportItem(Item $item, Mailer $mailer)
    {

        if ($this->isGranted('ROLE_USER')) {

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

            // FIXME: We need a form with reason and
            $mailer->sendReportMail($user, $item);

            // TODO: Add this to messages
            $this->addFlash('success', "The item was successfully reported and we send an email to its owner.");

            return $this->redirectToRoute('item_index');
        }
    }

    /**
     * @Route("/{id}/swap", name="item_swap", methods={"GET"})
     * @param Item $item
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function swapItem(Item $item, Mailer $mailer)
    {
        $user = $this->getUser();

        // FIXME: We need a form with reason and
        $mailer->sendSwapMail($user, $item);

        // TODO: Add this message to translation
        $this->addFlash('success', "We just sent an email to the owner informing you are interested.");

        return $this->redirectToRoute('item_index');
    }
}