<?php


namespace App\Controller;


use App\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reportAction(Mailer $mailer)
    {
        $user = $this->getUser();

        // FIXME: We need to get the item to pass it
        $mailer->sendReportMail($user, null);
        // Call Mailer report function

        $this->addFlash('success', "The item was reported successfully.");

        // TODO: use flash message on homepage and disable activation route
        return $this->redirectToRoute('homepage');
    }
}