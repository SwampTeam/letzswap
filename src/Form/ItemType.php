<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                ['label' => 'FORM.ITEM.DESCRIPTION.LABEL',
                    'required' => false
                ])
            ->add('conditionstatus', ChoiceType::class, ['label' => 'FORM.ITEM.STATUS.LABEL',
                'choices' => [
                    'FORM.ITEM.STATUS.NEW' => 'As New',
                    'FORM.ITEM.STATUS.SIGNS' => 'Signs of Wear',
                    'FORM.ITEM.STATUS.PARTS' => 'For Parts'
                ],
                'multiple' => false,
                'required' => true
            ]);

        if ($options['empty_data']) {
            $builder->add('picture', FileType::class,
                ['label' => 'FORM.ITEM.PICTURE.LABEL',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'mimeTypes' => ['image/png', 'image/jpeg'],
                            'mimeTypesMessage' => 'Please upload a jpeg or a png',
                            'maxSize' => '5M',
                            'minWidth' => 300,
                            'minHeight' => 300
                        ])
                    ]
                ]
            );
        } else {
            $builder->add('picture', FileType::class,
                ['label' => 'FORM.ITEM.PICTURE.LABEL',
                    'mapped' => false,
                    'constraints' => [
                        new Image([
                            'mimeTypes' => ['image/png', 'image/jpeg'],
                            'mimeTypesMessage' => 'Please upload a jpeg or a png',
                            'maxSize' => '5M',
                            'minWidth' => 300,
                            'minHeight' => 300
                        ])
                    ]
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Item::class]);
    }
}
