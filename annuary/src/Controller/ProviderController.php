<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Form\UpdateProviderType;
use App\Service\FileService;
use App\Entity\Internship;
use App\Repository\InternshipRepository;
use App\Form\InternshipType;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/provider')]
class ProviderController extends AbstractController
{

    private ProviderRepository $providerRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProviderRepository $providerRepository, EntityManagerInterface $entityManager) {
        $this->providerRepository = $providerRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Renders a provider's profile
     * @param $id int Provider ID
     * @param InternshipRepository $internshipRepository
     * @return mixed
     */
    #[Route('/{id}', name: 'provider_detail')]
    public function show(int $id, InternshipRepository $internshipRepository): mixed
    {
        // Fetch data of targeted provider
        $provider = $this->providerRepository->find($id);
        // Back to homepage if provider doesn't exist
        if(!$provider) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }
        // Find similar providers according to the categories & localization of the selected provider
        $similarProviders = $this->providerRepository->findSimilar($provider);

        return $this->render('provider/index.html.twig', [
            'provider' => $provider,
            'similarProviders' => $similarProviders,
        ]);
    }

    /**
     * Renders & handles form to update the provider's profile
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/update/{id}', name: 'provider_update')]
    #[IsGranted('ROLE_PROVIDER')]
    public function update(FileService $fileService, Request $request, int $id)
    {
        // Fetch data of targeted provider
        $provider = $this->providerRepository->find($id);
        // Restrict access to the owner of the internship if it exists
        if($this->getUser() &&
            $provider &&
            $this->isOwner($provider)) {

            // Create form
            // Passing the provider id in the options to get only the category selected by the provider
            $form = $this->createForm(UpdateProviderType::class, $provider, [
                'postCode' => $provider->getUser()->getLocality()->getPostCode()->getPostCode(),
            ]);
            // Handle form
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $provider = $form->getData();
                // If logo exist, save it to the right directory
                $logo = $form->get('logo')->getData();
                if($logo) {
                    try {
                        $logoFileName = $fileService->save($logo, Provider::LOGO_DIRECTORY);
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

    /**
     * Checks if the current user is the owner of the selected provider
     * @param Provider $provider
     * @return bool
     */
    public function isOwner(Provider $provider) {
        return $this->getUser()->getId() == $provider->getUser()->getId();
    }
}
