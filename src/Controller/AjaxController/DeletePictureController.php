<?php


namespace App\Controller\AjaxController;


use App\Repository\StudyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeletePictureController
{
    /**
     * @param Request $request
     * @param StudyRepository $studyRepository
     * @param KernelInterface $kernel
     * @return JsonResponse
     * @Route("/removepicture", name="remove_picture", methods={"POST"})
     */
    public function delete(Request $request, StudyRepository $studyRepository, KernelInterface $kernel, EntityManagerInterface $manager)
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        $data = json_decode($request->getContent(), true);

        $study = $studyRepository->findOneById($data['study']);
        $setter = 'setLogo' . $data['image'];
        $getter = 'getLogo' . $data['image'];
        $image = $study->{$getter}();
        try {
            $study->{$setter}(null);

            if(is_file($kernel->getProjectDir() . '/public' . $image)) {
                unlink($kernel->getProjectDir() . '/public' . $image);
            }
            $manager->persist($study);
            $manager->flush();
            $response->setStatusCode(Response::HTTP_OK);
        } catch(\Exception $e) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }

}
