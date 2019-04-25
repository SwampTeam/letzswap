<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'CONTACT.FORM.NAME', 'class' => "form_grey_input"],
                    'label' => false,
                ])
            ->add('subject',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'CONTACT.FORM.SUBJECT', 'class' => "form_grey_input"],
                    'label' => false,
                ])
            ->add('email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => 'CONTACT.FORM.EMAIL', 'class' => "form_grey_input"],
                    'label' => false,
                ])
            ->add('message',
                TextareaType::class,
                [
                    'attr' => ['placeholder' => 'CONTACT.FORM.MESSAGE', 'class' => "form_grey_input"],
                    'label' => false,
                ]);


        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                [
                    'label' => 'FORM.USER.SUBMIT',
                    'attr' => ['class' => 'button btn-block']
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'standalone' => false,
        ]);
    }
}
