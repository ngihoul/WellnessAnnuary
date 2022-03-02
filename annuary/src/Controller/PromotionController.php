<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;

#[Route('/promotion')]
class PromotionController extends AbstractController
{
    private PromotionRepository $promotionRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PromotionRepository $promotionRepository, EntityManagerInterface $entityManager) {
        $this->promotionRepository = $promotionRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/add', name: 'promotion_add')]
    #[IsGranted('ROLE_PROVIDER')]
    public function add(Request $request): Response
    {
        $title = 'Ajouter une promotion';
        $promotion = new Promotion();

        $form = $this->createForm(PromotionType::class, $promotion, [
            'provider' => $this->getUser()->getProvider()->getId(),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(($promotion->getStartAt() < $promotion->getEndAt()) && $promotion->getDisplayedFrom() < $promotion->getDisplayedUntil()) {

                $provider = $this->getUser()->getProvider();
                $provider->addPromotion($promotion);

                $this->entityManager->persist($provider);
                $this->entityManager->flush();

                $promotionName = $promotion->getName();

                $this->addFlash('success', "La promotion $promotionName a été ajoutée");
                return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#promotion' );
            } else {
                $this->addFlash('error', 'Veuillez corriger les dates. Il doit y avoir au moins 24h entre les dates.');
            }
        }

        return $this->renderForm('promotion/form.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }

    #[Route('/update/{id}', name: 'promotion_update')]
    #[IsGranted('ROLE_PROVIDER')]
    public function update(Request $request, $id): Response {
        $title = 'Modifier cette promotion';
        $promotion = $this->promotionRepository->find($id);

        if($this->getUser() &&
            $promotion &&
            $this->isOwner($promotion)) {

            $form = $this->createForm(PromotionType::class, $promotion, [
                'submit_label' => $title,
                'provider' => $this->getUser()->getProvider()->getId()
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                if(($promotion->getStartAt() < $promotion->getEndAt()) && $promotion->getDisplayedFrom() < $promotion->getDisplayedUntil()) {
                    $provider = $this->getUser()->getProvider();
                    $provider->addPromotion($promotion);

                    $this->entityManager->persist($provider);
                    $this->entityManager->flush();

                    $promotionName = $promotion->getName();

                    $this->addFlash('success', "La promotion $promotionName a été modifiée.");
                    return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#promotions' );
                } else {
                    $this->addFlash('error', 'Veuillez corriger les dates. Il doit y avoir au moins 24h entre les dates.');
                }
            }

            return $this->renderForm('promotion/form.html.twig', [
                'form' => $form,
                'title' => $title,
            ]);
        } else {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/delete/{id}', name: 'promotion_delete')]
    #[IsGranted('ROLE_PROVIDER')]
    public function delete(Request $request, $id): Response {
        $promotion = $this->promotionRepository->find($id);

        if($this->getUser() &&
            $promotion &&
            $this->isOwner($promotion)) {

            $provider = $this->getUser()->getProvider();
            $provider->removePromotion($promotion);
            $this->entityManager->flush();

            $promotionName = $promotion->getName();
            $this->addFlash('success', "La promotion $promotionName a été supprimée");

            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]).'#promotions' );
        } else {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }
    }

    public function isOwner(Promotion $promotion) {
        return $this->getUser()->getId() == $promotion->getProvider()->getUser()->getId();
    }
}
