<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Form\UpdateProviderType;
use App\Service\ImageService;
use App\Entity\Internship;
use App\Form\InternshipType;

#[Route('/provider')]
class ProviderController extends AbstractController
{

    private ProviderRepository $providerRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProviderRepository $providerRepository, EntityManagerInterface $entityManager) {
        $this->providerRepository = $providerRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'provider_home')]
    public function index(): Response
    {
        return $this->render('provider/index.html.twig', [
            'controller_name' => 'ProviderController',
        ]);
    }

    #[Route('/{id}', name: 'provider_detail')]
    public function show($id) {
        // Fetch data of targeted provider
        $provider = $this->providerRepository->find($id);

        if(!$provider) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        // Find similar providers according categories & localization
        $similarProviders = $this->providerRepository->findSimilar($provider);

        return $this->render('provider/index.html.twig', [
            'provider' => $provider,
            'similarProviders' => $similarProviders,
        ]);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    #[Route('/update/{id}', name: 'provider_update')]
    #[IsGranted('ROLE_PROVIDER')]
    public function update(ImageService $imageService, Request $request, $id) {
        // Fetch data of targeted provider
        $provider = $this->providerRepository->find($id);

        if($this->getUser() &&
            $provider &&
            $this->isOwner($provider)) {

            // Handling form
            $form = $this->createForm(UpdateProviderType::class, $provider, [
                'postCode' => $provider->getUser()->getLocality()->getPostCode()->getPostCode(),
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $provider = $form->getData();

                $logo = $form->get('logo')->getData();
                if($logo) {
                    try {
                        $logoFileName = $imageService->save($logo, Provider::LOGO_DIRECTORY);
                        $provider->setLogo($logoFileName);
                    } catch(FileException $e) {
                        $this->addFlash('error', 'Le fichier n\'a pas pu être enregistré car ' . $e->getMessage());
                    }
                }
                $this->entityManager->persist($provider);
                $this->entityManager->flush();

                return $this->redirectToRoute('provider_detail', ['id' => $provider->getId()]);
            }

            return $this->renderForm('provider/update_provider.html.twig', [
                'form' => $form,
                'image' => $provider->getLogo(),
            ]);
        } else {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

    }

    public function isOwner(Provider $provider) {
        return $this->getUser()->getId() == $provider->getUser()->getId();
    }
}
