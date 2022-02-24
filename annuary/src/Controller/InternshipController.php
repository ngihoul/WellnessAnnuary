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
    private InternshipRepository $internshipRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(InternshipRepository $internshipRepository, EntityManagerInterface $entityManager) {
        $this->internshipRepository = $internshipRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/add', name: 'internship_add')]
    #[IsGranted('ROLE_PROVIDER')]
    public function add(Request $request): Response {
        $title = 'Ajouter un stage';
        $internship = new Internship();

        $form = $this->createForm(InternshipType::class, $internship);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $internship->setDisplayedUntil($internship->getEndAt());

            $provider = $this->getUser()->getProvider();
            $provider->addInternship($internship);

            $this->entityManager->persist($provider);
            $this->entityManager->flush();

            $internshipName = $internship->getName();

            $this->addFlash('success', "Le stage $internshipName a été ajouté");
            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#internship' );
        }

        return $this->renderForm('internship/form.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }

    #[Route('/update/{id}', name: 'internship_update')]
    #[IsGranted('ROLE_PROVIDER')]
    public function update(Request $request, $id): Response {
        $title = 'Modifier ce stage';
        $internship = $this->internshipRepository->find($id);

        if($this->getUser() &&
            $internship &&
            $this->isOwner($internship)) {

            $form = $this->createForm(InternshipType::class, $internship, [
                'submit_label' => $title,
            ]);

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
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/delete/{id}', name: 'internship_delete')]
    #[IsGranted('ROLE_PROVIDER')]
    public function delete(Request $request, $id): Response {
        $internship = $this->internshipRepository->find($id);

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
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }
    }

    public function isOwner(Internship $internship) {
        return $this->getUser()->getId() == $internship->getProvider()->getUser()->getId();
    }
}
