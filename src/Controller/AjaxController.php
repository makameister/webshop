<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax", name="ajax")
     */
    public function index(Request $request, VilleRepository $repository, DepartementRepository $departementRepository, SerializerInterface $serializer)
    {
        $id = $request->query->get('id');

        $d = $departementRepository->find($id);

        $v = $repository->findBy(['departement' => $d]);

        $v2 = $serializer->serialize($v, 'json', ['groups' => 'groupe']);

        return new JsonResponse($v2);
    }
}
