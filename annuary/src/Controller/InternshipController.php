<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Internship;
use App\Form\InternshipType;
use App\Repository\InternshipRepository;

#[Route('/internship')]
class InternshipController extends AbstractController
{
    const MSG_PAGE_NOT_EXIST = 'Cette page n\'existe pas';

    private InternshipRepository $internshipRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor
     * @param InternshipRepository $internshipRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(InternshipRepository $internshipRepository, EntityManagerInterface $entityManager)
    {
        $this->internshipRepository = $internshipRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Renders & handles form to add a new internship
     * @param Request $request
     * @return Response
     */
    #[Route('/add', name: 'internship_add')]
    #[IsGranted('ROLE_PROVIDER')]
    public function add(Request $request): Response {
        $title = 'Ajouter un stage';
        $internship = new Internship();
        // Create form
        $form = $this->createForm(InternshipType::class, $internship);
        // Handle the form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $internship->setDisplayedUntil($internship->getEndAt());

            $provider = $this->getUser()->getProvider();
            $provider->addInternship($internship);

            $this->entityManager->persist($provider);
            $this->entityManager->flush();

            $internshipName = $internship->getName();
            $this->addFlash('success', "Le stage $internshipName a été ajouté.");

            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#internship' );
        }

        return $this->renderForm('internship/form.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }

    /**
     * Renders & handles form to update an internship
     * @param Request $request
     * @param $internshipId
     * @return Response
     */
    #[Route('/update/{id}', name: 'internship_update')]
    #[IsGranted('ROLE_PROVIDER')]
    public function update(Request $request, $internshipId): Response {
        $title = 'Modifier ce stage';
        // Get the internship
        $internship = $this->internshipRepository->find($internshipId);
        // Restrict access to the owner of the internship if it exists
        if($this->getUser() &&
        $internship &&
        $this->isOwner($internship)) {
            // Create form
            $form = $this->createForm(InternshipType::class, $internship, [
                'submit_label' => $title,
            ]);
            // Handle form
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $internship->setDisplayedUntil($internship->getEndAt());

                $provider = $this->getUser()->getProvider();
                $provider->addInternship($internship);

                $this->entityManager->persist($provider);
                $this->entityManager->flush();

                $internshipName = $internship->getName();
                $this->addFlash('success', "Le stage $internshipName a été modifié");

                return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#internship' );
            }

            return $this->renderForm('internship/form.html.twig', [
                'form' => $form,
                'title' => $title,
            ]);
        } else {
            $this->addFlash('error', Self::MSG_PAGE_NOT_EXIST);
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Deletes an internship
     * @param $internshipId
     * @return Response
     */
    #[Route('/delete/{id}', name: 'internship_delete')]
    #[IsGranted('ROLE_PROVIDER')]
    public function delete($internshipId): Response {
        // Get the internship
        $internship = $this->internshipRepository->find($internshipId);
        // Restrict access to the owner of the internship if it exists
        if($this->getUser() &&
        $internship &&
        $this->isOwner($internship)) {

            $provider = $this->getUser()->getProvider();

            $provider->removeInternship($internship);
            $this->entityManager->flush();

            $internshipName = $internship->getName();
            $this->addFlash('success', "Le stage $internshipName a été supprimé");

            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#internship' );
        } else {
            $this->addFlash('error', Self::MSG_PAGE_NOT_EXIST);
            return $this->redirectToRoute('home');
        }
    }

    /**
     * Checks if the current user is the owner of the selected internship
     * @param Internship $internship
     * @return bool
     */
    public function isOwner(Internship $internship) {
        return $this->getUser()->getId() == $internship->getProvider()->getUser()->getId();
    }
}
