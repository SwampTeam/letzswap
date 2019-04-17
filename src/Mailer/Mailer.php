<?php


namespace App\Mailer;


use App\Entity\Item;
use App\Entity\User;
use App\Form\ContactFormType;
use Twig\Environment;

class Mailer
{
    private $twig;
    private $mailer;
    private $recipient;
    private $subject;
    private $sender;
    private $txtTemplate;
    private $htmlTemplate;

    public function __construct(
        Environment $twig,
        \Swift_Mailer $mailer,
        string $recipient,
        string $subject,
        string $sender,
        string $txtTemplate,
        string $htmlTemplate
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->sender = $sender;
        $this->txtTemplate = $txtTemplate;
        $this->htmlTemplate = $htmlTemplate;
    }

    public function sendRegistrationMail(User $user)
    {
        $message = (new \Swift_Message())
            ->setSubject($this->subject)
            ->setFrom($this->sender)
            ->setTo($user->getEmail())
            ->setBody($this->twig->render($this->htmlTemplate, ['user' => $user]), 'text/html')
            ->addPart($this->twig->render($this->txtTemplate, ['user' => $user]), 'text/plain');
        $this->mailer->send($message);
    }

    public function sendSwapMail(User $user, Item $item)
    {
        $message = (new \Swift_Message())
            ->setSubject($item->getTitle() . ': someone is interested!')
            ->setFrom('no-reply@')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render($this->htmlTemplate, ['user' => $user]), 'text/html')
            ->addPart($this->twig->render($this->txtTemplate, ['user' => $user]), 'text/plain');
        $this->mailer->send($message);
    }

    public function sendMail()
    {
        $message = (new \Swift_Message())
            ->setSubject($this->subject)
            ->setFrom($this->sender)
            ->setTo($this->recipient)
            ->setBody($this->twig->render($this->htmlTemplate, ['user' => $user]), 'text/html')
            ->addPart($this->twig->render($this->txtTemplate, ['user' => $user]), 'text/plain');
        $this->mailer->send($message);
        $this->mailer->send($message);
    }
}