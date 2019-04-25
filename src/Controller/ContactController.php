<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Mailer\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{

    /**
     * @Route("/about", name="about")
     * @param Request $request
     * @param Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function contactAction(
        Request $request,
        Mailer $mailer
    ): Response
    {
        $form = $this->createForm(
            ContactFormType::class,
            null,
            ['standalone' => true]
        );

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $mailer->sendContactMail($data);

            $this->addFlash('success', "Your message was sent.");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('about/about.html.twig', [
            'contactForm' => $form->createView(),
            'uRoles' => ''
        ]);
    }
}

