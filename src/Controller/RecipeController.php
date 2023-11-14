<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Entity\Recipe;
use App\Form\OpinionType;
use App\Form\RecipeType;
use App\Repository\OpinionRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twig\Environment;

class RecipeController extends AbstractController
{
    /**
     * Display all recipes
     *
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/recipe', name: 'recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * Display one recipe
     * Give an opinion for connected user
     * 
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/recipe/show/{id}', name: 'recipe.show', methods: ['GET', 'POST'])]
    public function show(Recipe $recipe, Request $request, EntityManagerInterface $em, OpinionRepository $opinionRepository, Environment $environment): Response
    {
        $recipeIngredients = explode(',', $recipe->getIngredient());
        $recipeSteps = explode(';', $recipe->getSteps());

        $opinion = new Opinion();
        $opinion->setUser($this->getUser());
        $opinion->setRecipe($recipe);

        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinion = $form->getData();

            $em->persist($opinion);
            $em->flush();

            return new JsonResponse([
                'html' => $environment->render('opinion/index.html.twig', [
                    'opinion' => $opinion
                ])
            ]);
        }


        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'recipeIngredients' => $recipeIngredients,
            'recipeSteps' => $recipeSteps,
            'opinions' => $opinionRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * Create a recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/recipe/new', name: 'recipe.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $em->persist($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                'La nouvelle recette à bien été ajoutée.'
            );

            return $this->redirectToRoute('recipe');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit a recipe
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/recipe/edit/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $em->persist($recipe);
            $em->flush();

            $this->addFlash(
                'success',
                'La recette à bien été modifiée.'
            );

            return $this->redirectToRoute('recipe');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a recipe
     *
     * @param Recipe $recipe
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/recipe/delete/{id}', name: 'recipe.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash(
            'success',
            'La recette à bien été supprimée.'
        );

        return $this->redirectToRoute('recipe');
    }

    /**
     * Delete an opinion
     *
     * @param Opinion $recipe
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/opinion/delete/{id}', name: 'opinion.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteOpinion(Opinion $opinion, EntityManagerInterface $em): Response
    {
        $em->remove($opinion);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'avis à bien été supprimé.'
        );

        return $this->redirectToRoute('recipe');
    }
}
