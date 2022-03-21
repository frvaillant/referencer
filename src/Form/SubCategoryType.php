<?php

namespace App\Form;

use App\Entity\SubCategory;
use App\Repository\StudyCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubCategoryType extends AbstractType
{
    private $categoryRepository;

    public function __construct(StudyCategoryRepository $categoryRepository)
    {
        $this->categoryRepository =$categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', ChoiceType::class, [
                'choices' => $this->getCategories(),
                'required' => true
            ])
        ;
    }

    public function getCategories()
    {
        $categories = $this->categoryRepository->findAll([], ['name' => 'ASC']);
        $choices = [];
        foreach ($categories as $category) {
            $choices[$category->getName()] = $category;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubCategory::class,
        ]);
    }
}
