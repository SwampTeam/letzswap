<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'NAV.ADD_ITEM.USERNAME.LABEL'])
            ->add('title', TextType::class, ['label' => 'FORM.ITEM.TITLE.LABEL'])
            ->add('description', TextareaType::class,
                ['label' => 'NAV.ADD_ITEM.DESCRIPTION.LABEL',
                    'required' => false
                ])
            ->add('condition_status', EntityType::class, ['label' => 'NAV.ADD_ITEM.STATUS.LABEL',
                'class' => Item::class,
                'choice_label' => 'label',
                'multiple' => true,
                'required' => false
            ])
            ->add('picture', Picture::class,
                ['label' => 'NAV.ADD_ITEM.PICTURE.LABEL',
                    'mapped' => false,
                    'constraints' => [
                        new Picture([
                            'mimeTypes' => ['image/png', 'image/jpeg'],
                            'maxSize' => '6M',
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
                    'label' => 'NAV.ADD_ITEM.SUBMIT.LABEL',
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
