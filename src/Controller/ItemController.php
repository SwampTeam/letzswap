<?php

namespace App\Controller;

use App\Form\NewItemFormType;
use App\Entity\Item;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ItemController extends AbstractController
{
    /**
     * @Route("/newitem", name="new_item")
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     */
    public function addItem(Request $request,
                            FormFactoryInterface $formFactory,
                            Environment $twig)
    {
        $item = new Item();
        $form = $formFactory->create(
            NewItemFormType::class,
            $item,
            ['standalone' => true]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // open the image, getDate return the uploaded file
            $file = $form->get('file')->getData();
            // rename file
            $fileName = Uuid::uuid4()->toString() . '.' . 'pic';
            // save the file
            $item->setTitle();
            $item->setDescription();
            $item->setConditionStatus();
            $item->setUserId();
            $file->move($this->getParameter('upload_directory'), $fileName);


            // get doctrine
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        return new Response($twig->render('Item/add-item.html.twig', [
            'addItemForm' => $form->createView()
        ]));
    }
}
