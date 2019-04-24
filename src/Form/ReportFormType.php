<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reportReason', ChoiceType::class, ['label' => "I'm reporting because this item is:",
                'choices' => [
                    'Offensive' => 'Offensive',
                    'Inappropriate' => 'Inappropriate',
                    'Illegal' => 'Illegal'
                ],
                'multiple' => false,
                'required' => true
            ])
            ->add('message',
                TextareaType::class,
                [
                    'attr' => [
                        'placeholder' => "FIXME: Info to the interested party saying what happens when he sends this.
                             Do we need more complain reasons?",
                        'class' => "form_grey_input",
                    ],
                    'label' => false,
                ]);


        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                [
                    'label' => 'Report item',
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