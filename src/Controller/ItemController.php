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
     * @Route("/upload", name="upload_pictures")
     */
    public function upload(): Response
    {
        if (empty($_FILES) || $_FILES["file"]["error"]) {
            die('{"NOK": ' . print_r($_FILES, true) . '}');
        }

        $fileName = $_FILES["file"]["name"];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = Uuid::uuid4()->toString() . '.' . $ext;
        move_uploaded_file($_FILES["file"]["tmp_name"], "../public/uploads_tmp/$uniqueFileName");

        $res = new Response($uniqueFileName, Response::HTTP_OK);
        return $res;
    }

    /**
     * @param $data
     * @return string
     */
    protected function sanitize($data)
    {
        $sanIn = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $sanHttp = str_replace("http:", "", $sanIn);
        $sanFtp = str_replace("ftp:", "", $sanHttp);
        $sanOut = strtolower($sanFtp);

        return $sanOut;
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
        $form = $this->createForm(ItemType::class, $item, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the user who posted the item
            $item->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);

            // Picture processing
            $files = $form->get('pictures')->getData();
            $filesArray = explode(',', $files);
            if (!is_array($filesArray)) {
                $filesArray = [];
                $filesArray[] = $files;
            }
            foreach ($filesArray as $file) {
                $fileClean = $this->sanitize($file);
                if (file_exists($this->getParameter('upload_tmp_directory') . $fileClean)) {
                    $originalFilePath = $this->getParameter('upload_tmp_directory') . $fileClean;
                    $filePath = $this->getParameter('upload_directory') . $fileClean;
                    $picture = new Picture();
                    $picture->setPath($fileClean);
                    $picture->setMimeType(mime_content_type($originalFilePath));
                    $picture->setItem($item);
                    rename($originalFilePath, $filePath);
                    $entityManager->persist($picture);
                }
            }

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
            'formNew' => $form->createView(),
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
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('item_index', [
                'id' => $item->getId()
            ]);
        }
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'pictures' => $item->getPictures(),
            'formEdit' => $form->createView()
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
        $user = $this->getUser();

        // FIXME: We need a form with reason and
        $mailer->sendReportMail($user, $item);

        $this->addFlash('success', "We send an email to the owner.");

        // TODO: use flash message on item and disable activation route
        return $this->redirectToRoute('item_index');
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

        $this->addFlash('success', "The item was reported successfully.");

        // TODO: use flash message on item and disable activation route
        return $this->redirectToRoute('item_index');
    }
}