<?php

namespace App\Controller;

use App\Service\UserNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MessageController extends AbstractController
{
    /**
     * @Route("/send", name="send_message")
     * @param UserNotification $userNotification
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserNotification $userNotification)
    {
        $errorMessage = null;

        try {
            $userNotification->notifyByEmail(
                'User',
                'User wants to swap item with you' .
                $this->generateUrl('schedule', [], UrlGeneratorInterface::ABSOLUTE_URL)
            );
        } catch (Server | Request | Exception $exception) {
            $errorMessage = $exception->getMessage();
        }
        return $this->render('send_message/index.html.twig', [
            'controller_name' => 'MessageController',
            'errorMessage' => $errorMessage
        ]);
    }
}
