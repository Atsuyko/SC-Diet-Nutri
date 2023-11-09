<?php

namespace App\Controller;

use App\Entity\Diet;
use App\Form\DietType;
use App\Repository\DietRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DietController extends AbstractController
{
    /**
     * Display all diets
     *
     * @param DietRepository $dietRepository
     * @return Response
     */
    #[Route('/diet', name: 'diet', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(DietRepository $dietRepository): Response
    {
        $diets = $dietRepository->findAll();

        return $this->render('diet/index.html.twig', [
            'diets' => $diets,
        ]);
    }

    /**
     * Create a diet
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/diet/new', name: 'diet.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $diet = new Diet();
        $form = $this->createForm(DietType::class, $diet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $diet = $form->getData();

            $em->persist($diet);
            $em->flush();

            $this->addFlash(
                'success',
                'Le nouveau régime à bien été ajouté.'
            );

            return $this->redirectToRoute('diet');
        }

        return $this->render('diet/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit a diet
     *
     * @param Diet $diet
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/diet/edit/{id}', name: 'diet.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Diet $diet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DietType::class, $diet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $diet = $form->getData();

            $em->persist($diet);
            $em->flush();

            $this->addFlash(
                'success',
                'Le régime à bien été modifié.'
            );

            return $this->redirectToRoute('diet');
        }

        return $this->render('diet/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a diet
     *
     * @param Diet $diet
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/diet/delete/{id}', name: 'diet.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Diet $diet, EntityManagerInterface $em): Response
    {
        $em->remove($diet);
        $em->flush();

        $this->addFlash(
            'success',
            'Le régime à bien été supprimé.'
        );

        return $this->redirectToRoute('diet');
    }
}
