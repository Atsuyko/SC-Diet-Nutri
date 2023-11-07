<?php

namespace App\Controller;

use App\Entity\Diet;
use App\Form\DietType;
use App\Repository\DietRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietController extends AbstractController
{
    #[Route('/diet', name: 'diet', methods: ['GET'])]
    public function index(DietRepository $dietRepository): Response
    {
        $diets = $dietRepository->findAll();

        return $this->render('diet/index.html.twig', [
            'diets' => $diets,
        ]);
    }

    #[Route('/diet/new', name: 'diet.new', methods: ['GET', 'POST'])]
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
                'Le nouveau régime à bien été ajouter.'
            );

            return $this->redirectToRoute('diet');
        }

        return $this->render('diet/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
