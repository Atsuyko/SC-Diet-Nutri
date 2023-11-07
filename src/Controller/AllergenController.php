<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Form\AllergenType;
use App\Repository\AllergenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllergenController extends AbstractController
{
    #[Route('/allergen', name: 'allergen', methods: ['GET'])]
    public function index(AllergenRepository $allergenRepository): Response
    {
        $allergens = $allergenRepository->findAll();

        return $this->render('allergen/index.html.twig', [
            'allergens' => $allergens,
        ]);
    }

    #[Route('/allergen/new', name: 'allergen.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $allergen = $form->getData();

            $em->persist($allergen);
            $em->flush();

            $this->addFlash(
                'success',
                'Le nouvel allergène à bien été ajouter.'
            );

            return $this->redirectToRoute('allergen');
        }

        return $this->render('allergen/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
