<?php


namespace App\Form\Transformers;


use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CityTransformer implements DataTransformerInterface
{
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $city
     * @return string
     */
    public function transform($city)
    {
        if (null === $city) {

            return '';
        }
        return $city->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $cityId
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($cityId)
    {
        // no issue number? It's optional, so that's ok
        if (!$cityId) {
            return;
        }
        $cityId = (int)$cityId;
        $city = $this->cityRepository->findOneById($cityId);

        if (null === $city) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Aucune ville ne semble exister pour l\'ID n° "%s"',
                $cityId
            ));
        }
        return $city;
    }
}
