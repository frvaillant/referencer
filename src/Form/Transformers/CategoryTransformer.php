<?php


namespace App\Form\Transformers;


use App\Entity\StudyCategory;
use App\Repository\CityRepository;
use App\Repository\StudyCategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryTransformer implements DataTransformerInterface
{
    private $repository;

    public function __construct(StudyCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $entity
     * @return string
     */
    public function transform($entity)
    {
        if (null === $entity) {

            return '';
        }
        return $entity->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $entityId
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($entityId)
    {
        // no issue number? It's optional, so that's ok
        if (!$entityId) {
            return;
        }
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
        return $entity;
    }
}
