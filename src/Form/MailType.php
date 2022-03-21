<?php

namespace App\Form;

use App\Repository\StructureRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MailType extends AbstractType
{

    /**
     * @var StructureRepository
     */
    private $structureRepository;

    public function __construct(StructureRepository $structureRepository)
    {
        $this->structureRepository = $structureRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('object', TextType::class, [
                'required' => true,
                'data' => $this->getObject(),
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sendTo', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'data' => 'Veuillez trouver ci-joint notre dossier de références.',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    public function getObject()
    {
        $structure = $this->structureRepository->findMyStructure();
        return 'Dossier de références de ' . $structure->getName();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
