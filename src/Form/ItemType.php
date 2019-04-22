<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Item data
        $builder
            ->add('title', TextType::class, ['label' => 'FORM.ITEM.TITLE.LABEL'])
            ->add('description', TextareaType::class,
                ['label' => 'NAV.ADD_ITEM.DESCRIPTION.LABEL',
                    'required' => false
                ])
            ->add('conditionstatus', ChoiceType::class, [
                'label' => 'NAV.ADD_ITEM.STATUS.LABEL',
                'multiple' => false,
                'required' => true,
                'choices' => [
                    'As New' => 'As New',
                    'Signs of Wear' => 'Signs of Wear',
                    'For Parts' => 'For Parts'
                ]]);

        // Pictures data
        $builder
//            ->add('pictures', HiddenType::class,
//                ['label' => 'NAV.ADD_ITEM.PICTURE.LABEL',
//                    'required' => true,
//                    'attr' => ['value' => '']])
            ->add(
                'Browse',
                ButtonType::class,
                ['label' => 'NAV.ADD_PICTURE.BROWSE.LABEL',
                    'attr' => [
                        'class' => 'btn btn-primary browse',
                        'id' => 'browse']]
            )
            ->add(
                'Upload',
                ButtonType::class,
                ['label' => 'NAV.ADD_PICTURE.UPLOAD.LABEL',
                    'attr' => [
                        'class' => 'btn btn-primary upload',
                        'id' => 'upload']]
            );

        if ($options['standalone']) {
            $builder->add(
                'Submit',
                SubmitType::class,
                ['label' => 'NAV.ADD_ITEM.SUBMIT.LABEL', 'attr' => ['class' => 'button']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'standalone' => false
        ]);
    }
}
