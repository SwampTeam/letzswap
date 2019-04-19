<?php


namespace App\Twig;


use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ModalFormExtension extends AbstractExtension
{
    private $formFactory;

    private $urlGenerator;

    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'createRegisterModal',
                [$this, 'createRegisterModal'],
                ['is_safe' => ['html'], 'needs_environment' => true])
        ];
    }

    public function createRegisterModal(Environment $twig)
    {
        $user = new User();
        $form = $this->formFactory->create(
            RegistrationFormType::class,
            $user,
            [
                'standalone' => true,
                'action' => $this->urlGenerator->generate('register')
            ]
        );

        return $twig->render('Modal/register_modal.html.twig', ['form' => $form->createView()]);
    }
}