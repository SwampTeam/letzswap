<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Status;
use App\Entity\ItemStatus;
use App\Entity\Picture;
use App\Form\ItemType;
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
     * @Route("/", name="item_index", methods={"GET"})
     * @param ItemRepository $itemRepository
     * @param PictureRepository $pictureRepository
     * @return Response
     */
    public function getItems(ItemRepository $itemRepository, PictureRepository $pictureRepository): Response
    {
        return $this->render('Main/index.html.twig', [
            'items' => $itemRepository->findAll(),
            'pictures' => $pictureRepository->findAll(),
        ]);
    }

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
        $picture = new Picture();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the user who posted the item
            $item->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);

            // Picture processing
            $file = $form->get('picture')->getData();
            $fileName = Uuid::uuid4()->toString() . '.swp';
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
            // Manager do your job
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
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

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function editItem(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index', [
                'id' => $item->getId(),
            ]);
        }
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
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
}