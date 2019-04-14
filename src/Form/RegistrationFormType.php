<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /* TODO: How to assign admin role? Another form?
               TODO: Verify if user role is automatically assigned  */
            ->add('username', TextType::class, ['label' => 'FORM.USER.USERNAME'])
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'FORM.USER.PASSWORD.ERROR.BLANK',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'FORM.USER.PASSWORD.ERROR.LENGTH',
                            'max' => 255,
                        ]),
                    ],
                    'invalid_message' => 'FORM.USER.PASSWORD.ERROR.DONT_MATCH',
                    'first_options' => ['label' => 'FORM.USER.PASSWORD.FIRST'],
                    'second_options' => ['label' => 'FORM.USER.PASSWORD.REPEAT'],
                ]
            )->add(
                'email',
                EmailType::class,
                ['label' => 'FORM.USER.EMAIL']
            )->add(
                'tosAccepted',
                CheckboxType::class,
                ['label' => 'FORM.USER.TOS_ACCEPTED']
            );
        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                [
                    'label' => 'FORM.USER.SUBMIT',
                    'attr' => ['class' => 'btn-success']
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false,
        ]);
    }
}
