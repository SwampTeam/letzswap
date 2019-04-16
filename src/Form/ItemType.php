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
            ->add('title', TextType::class, ['label' => 'FORM.ITEM.TITLE.LABEL'])
            ->add('description', TextareaType::class,
                ['label' => 'NAV.ADD_ITEM.DESCRIPTION.LABEL',
                    'required' => false
                ])
            ->add('conditionstatus', ChoiceType::class, ['label' => 'NAV.ADD_ITEM.STATUS.LABEL',
                'choices'  => [
                    'Brand new' => 'Brand new',
                    'Used once' => 'Used once',
                    "Don't work" => "Don't work"
                ],
                'multiple' => false,
                'required' => true,
                'constraints'=> []
            ])
            ->add('picture', FileType::class,
                ['label' => 'NAV.ADD_ITEM.PICTURE.LABEL',
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

            )
//            ->add('getId', EntityType::class, ['label' => 'NAV.ADD_ITEM.USERNAME.LABEL', 'class' => User::class]);
;

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
