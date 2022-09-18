<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\DepartementRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, DepartementRepository $departementRepository, VilleRepository $villeRepository, SerializerInterface $serializer)
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        $departements = $departementRepository->findAll();

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($personne);
            $entityManager->flush();
            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView(),
            'departements' => $departements
        ]);
    }
}
