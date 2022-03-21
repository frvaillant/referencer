<?php


namespace App\Controller\City;


use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * @Route("/cities", name="cities", methods={"POST"})
     */
    public function getCities(Request $request, CityRepository $cityRepository)
    {
        $data = json_decode($request->getContent());
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        $cities = $cityRepository->findWhereNameLike($data->cityName);
        if (count($cities) > 0) {
            $response->setStatusCode(Response::HTTP_OK);
            $cityShort = [];
            foreach ($cities as $city) {
                $cityShort[] = [
                    'id' => $city->getId(),
                    'name' => $city->getCityName(),
                    'zip' => $city->getZipCode()
                ];
            }
            $response->setData($cityShort);
        }

        return $response;
    }

}
