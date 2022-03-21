<?php


namespace App\Form\Transformers;


use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SubCategoryTransformer implements DataTransformerInterface
{
    private $repository;

    public function __construct(SubCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $entities
     * @return string
     */
    public function transform($entities)
    {
        $array = [];

        if (null === $entities) {

            return [];
        }

        foreach ($entities as $entity) {
            $array[] = $entity->getId();
        }
        return $array;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param $entities
     * @return array
     */
    public function reverseTransform($entities)
    {
        // no issue number? It's optional, so that's ok
        if (!$entities) {
            return [];
        }
        $array = [];
        foreach ($entities as $entityId) {
            $entityId = (int)$entityId;
            $entity = $this->repository->findOneById($entityId);
            if (null === $entity) {
                // causes a validation error
                // this message is not shown to the user
                // see the invalid_message option
                throw new TransformationFailedException(sprintf(
                    'Aucune entité ne semble exister pour l\'ID n° "%s"',
                    $entityId
                ));
            }

            $array[] = $entity;
        }
        return $array;
    }
}
