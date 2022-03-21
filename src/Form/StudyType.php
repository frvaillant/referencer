<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Study;
use App\Entity\StudyCategory;
use App\Form\Transformers\CategoryTransformer;
use App\Form\Transformers\CityTransformer;
use App\Form\Transformers\SubCategoryTransformer;
use App\Repository\CityRepository;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\StudyCategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Image;

class StudyType extends AbstractType
{

    private $clientRepository;
    private $subCategoryRepository;
    private $projectRepository;
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var StudyCategoryRepository
     */
    private $categoryRepository;
    /**
     * @var CityTransformer
     */
    private $cityTransformer;
    /**
     * @var CategoryTransformer
     */
    private $categoryTransformer;
    /**
     * @var SubCategoryTransformer
     */
    private $subCategoryTransformer;
    private $study;

    public function __construct(
        ClientRepository $clientRepository,
        SubCategoryRepository $subCategoryRepository,
        StudyCategoryRepository $studyCategoryRepository,
        ProjectRepository $projectRepository,
        CityRepository $cityRepository,
        CityTransformer $cityTransformer,
        CategoryTransformer $categoryTransformer,
        SubCategoryTransformer $subCategoryTransformer
    ) {
        $this->clientRepository      = $clientRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->projectRepository     = $projectRepository;
        $this->cityRepository        = $cityRepository;
        $this->cityTransformer       = $cityTransformer;
        $this->categoryRepository    = $studyCategoryRepository;
        $this->categoryTransformer   = $categoryTransformer;
        $this->subCategoryTransformer = $subCategoryTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $study = $builder->getData();
        $this->study = $study;
        $builder
            ->add('title')
            ->add('imageFile', FileType::class, [
                'required'  => false,
                'mapped'    => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '8M'
                    ])
                ],
                'attr' => [
                    'class' => 'fileuploader'
                ]
            ])
            ->add('imageFile2', FileType::class, [
                'required'  => false,
                'mapped'    => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '8M'
                    ])
                ],
                'attr' => [
                    'class' => 'fileuploader'
                ]
            ])
            ->add('startDate', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => ''
                ],
            ])
            ->add('endDate', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => '',
                ],
            ])
            ->add('cityName', TextType::class, [
                'data' => $study->getCity() ? $study->getCity()->getCityName() : '',
                'mapped' => false,
                'required' => false,
            ])
            ->add('city', HiddenType::class, [
                'required' => false,
            ])
            ->add('project', ChoiceType::class, [
                'choices' => $this->getProjects(),
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->getCategories()
            ])
            ->add('sub_categories', ChoiceType::class, [
                'choices' => $this->getSubCategories($study),
                'required' => false,
                'multiple' => true,
                'choice_attr' => function($choice, $key, $value) {

                        return $this->getChecked($value);

                },
            ])
            ->add('client', ChoiceType::class, [
                'choices' => $this->getClients(),
            ])
            ->add('projectManager', ChoiceType::class, [
                'required' => false,
                'choices' => $this->getClients('manager'),
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'materialize-textarea'
                ]
            ])
            ->add('additionalDescription', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'materialize-textarea'
                ]
            ])
            ->add('keywords', TextType::class, [
                'mapped'   => false,
                'required' => false
            ])
            ->add('hideClient', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'filled-in'
                ]
            ])
            ->add('hideCity', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'filled-in'
                ]
            ])
            ->add('hideProjectManager', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'filled-in'
                ]
            ])
        ;

        $builder->get('city')
            ->addModelTransformer($this->cityTransformer);
        $builder->get('category')
            ->addModelTransformer($this->categoryTransformer);
        $builder->get('sub_categories')->resetViewTransformers();

        $builder->get('sub_categories')
            ->addModelTransformer($this->subCategoryTransformer);

        /*$builder->get('sub_categories')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
            }
        );*/

    }

    public function getChecked($value)
    {
        $subCategories = $this->study->getSubCategories();
        $ids = [];
        foreach ($subCategories as $subCategory) {
            $ids[] = $subCategory->getId();
        }
        if (in_array($value, $ids)) {
            return ['selected' => 'selected'];
        }
        return [];
    }

    public function getClients($destiny = null)
    {
        $clients = $this->clientRepository->findAll([], ['name' => 'ASC']);
        $choices = [];
        if ('manager' === $destiny) {
            $choices['Choisir le maître d\'œuvre'] = '';
        } else {
            $choices['Choisir le client'] = '';
        }
        foreach ($clients as $client) {
            $choices[$client->getName()] = $client;
        }
        return $choices;
    }

    public function getSubCategories(?Study $study)
    {
        $subCategories = $this->subCategoryRepository->findBy([], [
            'name' => 'ASC'
        ]);

        if ($study && $study->getCategory() instanceof StudyCategory) {
            $subCategories = $this->subCategoryRepository->findBy([
                'category' => $study->getCategory()
                ],
                ['name' => 'ASC']
            );
        }

        $choices = [];
        foreach ($subCategories as $subCategory) {
            $choices[$subCategory->getName()] = $subCategory->getId();
        }
        return $choices;
    }

    public function getCategories()
    {
        $categories = $this->categoryRepository->findAll([], ['name' => 'ASC']);
        $choices = [];
        $choices['Choisir une catégorie'] = '';
        foreach ($categories as $category) {
            $choices[$category->getName()] = $category->getId();
        }
        return $choices;
    }

    public function getProjects()
    {
        $projects = $this->projectRepository->findAll([], ['name' => 'ASC']);
        $choices = [];
        $choices['Choisir un type de projet'] = '';
        foreach ($projects as $project) {
            $choices[$project->getName()] = $project;
        }
        return $choices;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Study::class,
            'allow_extra_fields' => true,
        ]);
    }


}
