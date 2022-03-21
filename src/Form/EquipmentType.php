<?php

namespace App\Form;

use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('brand', TextType::class, [
                'required' => false
            ])
            ->add('model', TextType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'attr'=> [
                    'class' => 'materialize-textarea'
                ],
                'required' => false
            ])
            ->add('quantity', NumberType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
