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
                'Le nouvel allergène à bien été ajouté.'
            );

            return $this->redirectToRoute('allergen');
        }

        return $this->render('allergen/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/allergen/edit/{id}', name: 'allergen.edit', methods: ['GET', 'POST'])]
    public function edit(Allergen $allergen, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AllergenType::class, $allergen);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $allergen = $form->getData();

            $em->persist($allergen);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'allergène à bien été modifié.'
            );

            return $this->redirectToRoute('allergen');
        }

        return $this->render('allergen/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/allergen/delete/{id}', name: 'allergen.delete', methods: ['GET'])]
    public function delete(Allergen $allergen, EntityManagerInterface $em): Response
    {
        $em->remove($allergen);
        $em->flush();

        $this->addFlash(
            'success',
            'L\allergen à bien été supprimé.'
        );

        return $this->redirectToRoute('allergen');
    }
}
