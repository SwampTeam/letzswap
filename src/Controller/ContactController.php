<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
/**
* @Route("/about", name="about")
*/
public function SendMessage(Request $request, \Swift_Mailer $mailer)
{
    $form + $this->createForm(ContactType::class);

    $form->handleRequest($request);

    $this->addFlash('info', 'Some useful info');

    if ($form->isSubmitted() && $form->isValid()) {

        $contactFormData = $form->getData();

        dump($contactFormData);

        $message = (new \Swift_Message('You received a swap request'))
            ->setForm($contactFormData ['email'])
            ->setTo('recipient@exemple.com')
            ->setBody(
                $contactFormData ['message'],
                'text/plain'
            );
        $mailer->send($message);

        $this->addFlash('success', 'It sent!');

        return $this->redirectToRoute('contact');
    }

    return $this->render('about/about.html.twig', [
        'our form' => $form->createView()

    ]);
    }
}

