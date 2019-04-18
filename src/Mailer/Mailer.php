<?php


namespace App\Mailer;


use App\Entity\Item;
use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $twig;
    private $mailer;
    private $defaultRecipient;
    private $defaultSender;
    private $htmlContactTemplate;
    private $txtContactTemplate;
    private $htmlRegistrationTemplate;
    private $txtRegistrationTemplate;
    private $htmlSwapTemplate;
    private $txtSwapTemplate;

    public function __construct(
        Environment $twig,
        \Swift_Mailer $mailer,
        string $defaultRecipient,
        string $defaultSender,
        string $htmlContactTemplate,
        string $txtContactTemplate,
        string $htmlRegistrationTemplate,
        string $txtRegistrationTemplate,
        string $htmlSwapTemplate,
        string $txtSwapTemplate
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->defaultSender = $defaultSender;
        $this->defaultRecipient = $defaultRecipient;
        $this->htmlContactTemplate = $htmlContactTemplate;
        $this->txtContactTemplate = $txtContactTemplate;
        $this->htmlRegistrationTemplate = $htmlRegistrationTemplate;
        $this->txtRegistrationTemplate = $txtRegistrationTemplate;
        $this->htmlSwapTemplate = $htmlSwapTemplate;
        $this->txtSwapTemplate = $txtSwapTemplate;
    }

    public function sendRegistrationMail(User $user, string $subject)
    {
        $message = (new \Swift_Message())
            ->setFrom($this->defaultSender)
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

    public function sendSwapMail(User $user, Item $item)
    {
        $message = (new \Swift_Message())
            ->setSubject($item->getTitle() . ': someone is interested!')
            ->setFrom($this->defaultSender)
            ->setTo($item->getUser()->getEmail())
            ->setBody($this->twig->render($this->htmlSwapTemplate,
                ['user' => $user, 'item' => $item]),
                'text/html')
            ->addPart($this->twig->render($this->txtSwapTemplate,
                ['user' => $user, 'item' => $item]),
                'text/plain');
        $this->mailer->send($message);
    }

    public function sendContactMail(string $senderName, string $senderEmail, string $text)
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($this->defaultSender)
            ->setTo($this->defaultRecipient)
            ->setBody($this->twig->render($this->htmlContactTemplate, ['name' => $senderName, 'email' => $senderEmail, 'message' => $text]),
                'text/html')
            ->addPart($this->twig->render($this->txtContactTemplate, ['name' => $senderName, 'email' => $senderEmail, 'message' => $text]),
                'text/plain');
        $this->mailer->send($message);
    }
    
}