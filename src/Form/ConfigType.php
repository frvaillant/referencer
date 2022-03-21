<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apiAuthorizedIp', TextType::class, [
                'required' => false,
            ])
            ->add('sender', TextType::class, [
                'data' => null,
                'required' => false,
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('login', TextType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('smtp', TextType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('port', NumberType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('encrypt', ChoiceType::class, [
                'data' => null,
                'required' => false,
                'choices' => [
                    'tls' => 'tls',
                    'ssl' => 'ssl'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
