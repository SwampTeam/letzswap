<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserStatus;
use App\Form\RegistrationFormType;
use App\Mailer\Mailer;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function registrationAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        Mailer $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(
            RegistrationFormType::class,
            $user,
            ['standalone' => true]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $user->setActivationToken(Uuid::uuid4());
            $subject = 'Please, activate your account';
            $mailer->sendRegistrationMail($user, $subject);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('warning', "You need to activate your account, please check your email.");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Registration/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/activation/required", name="account_activation_required")
     */
    public function activationRequired()
    {
        return $this->render('Registration/activation-required.html.twig');
    }

    /**
     * @Route("/account/activation/{token}", name="account_activation_token")
     * @param string $token
     * @param TokenStorageInterface $tokenStorage
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activationToken(
        string $token,
        TokenStorageInterface $tokenStorage
    )
    {
        $manager = $this->getDoctrine()->getManager();
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->findOneByActivationToken($token);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $status = $manager->getRepository(Status::class)->findOneByLabel('active');
        if (!$status) {
            $status = new Status();
            $status->setLabel('active');
            $manager->persist($status);
        }

        $userStatus = new UserStatus();
        $userStatus->setUser($user)
            ->setStatus($status);
        $manager->persist($userStatus);

        $user->setActivationToken(null)->setEmailConfirmed(true);
        $manager->flush();

        $tokenStorage->setToken(
            new UsernamePasswordToken($user,
                null,
                'main',
                $user->getRoles())
        );

        return $this->redirectToRoute('homepage');
    }
}
