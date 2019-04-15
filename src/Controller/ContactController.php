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
public function SendMessage(Request $request, $name, \Swift_Mailer $mailer)
{
$message = (new \Swift_Message(''));
$form = $this->createForm(ContactType::class, $message, ['standalone' => true]);
$form->handleRequest($request)
    ->setFrom('send@example.com')
    ->setTo('recipient@example.com')
    ->setBody(
        $this->renderView(
        // templates/emails/registration.html.twig
            'about/about.html.twig',
            ['name' => $name]
        ),
        'text/html'
    )/*
     * If you also want to include a plaintext version of the message
    ->addPart(
        $this->renderView(
            'emails/registration.txt.twig',
            ['name' => $name]
        ),
        'text/plain'
    )
    */
;

$mailer->send($message);


{
    return $this->render('about/about.html.twig', [
        'controller_name' => 'ContactController',
    ]);
}
}
}

