<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Display all contacts
     *
     * @param ContactRepository $contactRepository
     * @return Response
     */
    #[Route('/contact', name: 'contact')]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * Create a new contact
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/contact/new', name: 'contact.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setLastname($this->getUser()->getLastname())
                ->setFirstname($this->getUser()->getFirstname())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'success',
                'Je vous remercie pour votre message, je vous réponds dans les meilleurs délais.'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('contact/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete a contact
     *
     * @param Contact $contact
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/contact/delete/{id}', name: 'contact.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Contact $contact, EntityManagerInterface $em): Response
    {
        $em->remove($contact);
        $em->flush();

        $this->addFlash(
            'success',
            'La demande à bien été supprimée.'
        );

        return $this->redirectToRoute('contact');
    }
}
