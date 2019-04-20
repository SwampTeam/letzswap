<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class,
                ['label' => 'NAV.ADD_ITEM.PICTURE.LABEL',
                    'entry_type' => ItemType::class,
                    'mapped' => false,
                    'constraints' => [
                        new Image([
                            'mimeTypes' => ['image/png', 'image/jpeg'],
                            'mimeTypesMessage' => 'Please upload a jpeg or a png',
                            'maxSize' => '10M',
                            'minWidth' => 300,
                            'minHeight' => 300
                        ])
                    ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
            'inherit_data' => true,
            'standalone' => false
        ]);
    }

}