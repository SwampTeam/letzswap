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


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'FORM.USER.USERNAME', 'required' => true])
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'FORM.USER.PASSWORD.ERROR.DONT_MATCH',
                    'first_options' => ['label' => 'FORM.USER.PASSWORD.FIRST', 'required' => true],
                    'second_options' => ['label' => 'FORM.USER.PASSWORD.REPEAT', 'required' => true],
                ]
            )->add(
                'email',
                EmailType::class,
                ['label' => 'FORM.USER.EMAIL']
            )->add(
                'tosAccepted',
                CheckboxType::class,
    ['label' => 'FORM.USER.TOS_ACCEPTED', 'required' => true]
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
