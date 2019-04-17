<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Mailer\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/about", name="about")
 * @param Request $request
 * @param Mailer $mailer
 * @return Response
 * @throws \Exception
 */
class ContactController extends AbstractController
{

public function contactAction(
    Request $request,
    Mailer $mailer
) : Response
{
    $form = $this->createForm(
        ContactFormType::class,
        ['standalone' => true]
    );

    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {

        $mailer->sendMail();

        $this->addFlash('success', "Your message was sent.");

        return $this->redirectToRoute('homepage');
    }

    return $this->render('About/about.html.twig', [
        'contactForm' => $form->createView(),
    ]);
    }
}

