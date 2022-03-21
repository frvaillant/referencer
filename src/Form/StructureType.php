<?php

namespace App\Form;

use App\Entity\Structure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class StructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('adress1')
            ->add('address2')
            ->add('address3')
            ->add('zipCode')
            ->add('city')
            ->add('phoneNumber1')
            ->add('phoneNumber2')
            ->add('email', EmailType::class)
            ->add('website')
            ->add('siret')
            ->add('imageFile', FileType::class, [
                'required'  => false,
                'mapped'    => false
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'materialize-textarea'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
