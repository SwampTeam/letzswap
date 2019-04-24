<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwapFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('message',
                TextareaType::class,
                [
                    'attr' => [
                        'placeholder' => "Here you write your message and what item would you like to swap with!",
                        'class' => "form_grey_input"
                    ],
                    'label' => false,
                ]);


        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                [
                    'label' => 'Send message',
                    'attr' => ['class' => 'button']
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