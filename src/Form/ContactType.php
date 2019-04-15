<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function AboutPageForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'CONTACT.FORM.EMAIL'])
            ->add('message', TextareaType::class, ['label => CONTACT.FORM.MESSAGE']);

        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                ['label' => 'CONTACT.FORM.SUBMIT',
                    'attr' => ['class' => 'btn-success']
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false
        ]);

    }
}
