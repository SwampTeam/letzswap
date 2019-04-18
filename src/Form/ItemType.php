<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'ADD_ITEM.TITLE'])
            ->add('description', TextareaType::class,
                ['label' => 'ADD_ITEM.DESCRIPTION',
                    'required' => false
                ])
            ->add('conditionstatus', ChoiceType::class, ['label' => 'ADD_ITEM.STATUS',
                'choices' => [
                    'As New' => 'As New',
                    'Signs of Wear' => 'Signs of Wear',
                    'For Parts' => 'For Parts'
                ],
                'multiple' => false,
                'required' => true
            ])
            ->add('picture', FileType::class,
                ['label' => 'ADD_ITEM.PICTURE',
                    'mapped' => false,
                    'constraints' => [
                        new Image([
                            'mimeTypes' => ['image/png', 'image/jpeg'],
                            'maxSize' => '5M',
                            'minWidth' => 300,
                            'minHeight' => 300
                        ])
                    ]
                ]
            );

        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                [
                    'label' => 'ADD_ITEM.SUBMIT',
                    'attr' => [
                        'class' => 'btn-success'
                    ]
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'standalone' => false
        ]);
    }
}
