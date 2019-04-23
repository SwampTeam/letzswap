<?php


namespace App\Mailer;


use App\Entity\Item;
use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $twig;
    private $mailer;
    private $letzswapContactEmail;
    private $letzswapNoReplyEmail;
    private $htmlContactTemplate;
    private $txtContactTemplate;
    private $htmlRegistrationTemplate;
    private $txtRegistrationTemplate;
    private $htmlReportTemplate;
    private $txtReportTemplate;
    private $htmlSwapTemplate;
    private $txtSwapTemplate;

    public function __construct(
        Environment $twig,
        \Swift_Mailer $mailer,
        string $letzswapContactEmail,
        string $letzswapNoReplyEmail,
        string $htmlContactTemplate,
        string $txtContactTemplate,
        string $htmlRegistrationTemplate,
        string $txtRegistrationTemplate,
        string $htmlReportTemplate,
        string $txtReportTemplate,
        string $htmlSwapTemplate,
        string $txtSwapTemplate
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->letzswapNoReplyEmail = $letzswapNoReplyEmail;
        $this->letzswapContactEmail = $letzswapContactEmail;
        $this->htmlContactTemplate = $htmlContactTemplate;
        $this->txtContactTemplate = $txtContactTemplate;
        $this->htmlRegistrationTemplate = $htmlRegistrationTemplate;
        $this->txtRegistrationTemplate = $txtRegistrationTemplate;
        $this->htmlReportTemplate = $htmlReportTemplate;
        $this->txtReportTemplate = $txtReportTemplate;
        $this->htmlSwapTemplate = $htmlSwapTemplate;
        $this->txtSwapTemplate = $txtSwapTemplate;
    }

    // Working
    public function sendRegistrationMail(User $user, string $subject)
    {
        $message = (new \Swift_Message())
            ->setFrom($this->letzswapNoReplyEmail)
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setBody($this->twig->render($this->htmlRegistrationTemplate,
                ['user' => $user]),
                'text/html')
            ->addPart($this->twig->render($this->txtRegistrationTemplate,
                ['user' => $user]),
                'text/plain'
            );

        $this->mailer->send($message);
    }

    public function sendSwapMail(User $user, Item $item, array $data)
    {
        $text = $data["message"];
        $message = (new \Swift_Message())
            ->setSubject($item->getTitle() . ': someone is interested!')
            ->setFrom($this->letzswapNoReplyEmail)
            ->setTo($item->getUser()->getEmail())
            ->setBody($this->twig->render($this->htmlSwapTemplate,
                ['user' => $user, 'item' => $item, 'text-coming-from-swapForm' => $text]),
                'text/html')
            ->addPart($this->twig->render($this->txtSwapTemplate,
                ['user' => $user, 'item' => $item, 'text-coming-from-swapForm' => $text]),
                'text/plain');
        $this->mailer->send($message);
    }


    public function sendReportMail(User $user, Item $item, array $data)
    {
        $text = $data["message"];
        $message = (new \Swift_Message())
            // FIXME: We need form data to pass to the email
            ->setSubject(($item->getTitle()). ': was reported!')
            ->setFrom($this->letzswapNoReplyEmail)
            ->setTo($this->letzswapContactEmail)
            ->setBody($this->twig->render($this->htmlReportTemplate,
                ['user' => $user, 'item' => $item, 'text-coming-from-reportForm' => $text]),
                'text/html')
            ->addPart($this->twig->render($this->txtReportTemplate,
                ['user' => $user, 'item' => $item, 'text-coming-from-reportForm' => $text]),
                'text/plain');
        $this->mailer->send($message);
    }

    // Working
    public function sendContactMail(array $data)
    {
        $message = (new \Swift_Message())
            ->setSubject($data["subject"])
            ->setFrom($this->letzswapNoReplyEmail)
            ->setTo($this->letzswapContactEmail)
            ->setBody($this->twig->render($this->htmlContactTemplate, ['data' => $data]),
                'text/html')
            ->addPart($this->twig->render($this->txtContactTemplate, ['data' => $data]),
                'text/plain');
        $this->mailer->send($message);
    }
    
}