<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UpdateUserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * Display all users
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/user', name: 'user')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param LoginAuthenticator $authenticator
     * @return Response
     */
    #[Route('/user/new', name: 'user.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form =  $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le nouvel utilisateur à bien été ajouté.'
            );

            return $this->redirectToRoute('user');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a user
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/user/edit/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UpdateUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur à bien été modifié.'
            );

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a user
     *
     * @param User $user
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/user/delete/{id}', name: 'user.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur à bien été supprimé.'
        );

        return $this->redirectToRoute('user');
    }

    #[Route('/user/password/{id}', name: 'user.password', methods: ['GET', 'POST'])]
    public function editPassword(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($userPasswordHasher->isPasswordValid($user, $form->getData()['password'])) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Votre password à bien été modifié.'
                );

                return $this->redirectToRoute('recipe');
            } else {
                $this->addFlash(
                    'secondary',
                    'Votre password est incorrect.'
                );
            }
        }

        return $this->render('user/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
