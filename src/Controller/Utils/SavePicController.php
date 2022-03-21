<?php


namespace App\Controller\Utils;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class SavePicController extends AbstractController
{
    /**
     * @Route("/savepic", name="save_pic")
     */
    public function saver(Request $request, KernelInterface $kernel)
    {
        $response = new JsonResponse();
        $data = json_decode($request->getContent(), true);
        $img = $data['img'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $img = base64_decode($img);

        $file = $kernel->getProjectDir() . '/public/uploads/synthese.png';

        $result = file_put_contents($file, $img);

        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

}
