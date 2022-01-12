<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProviderRepository;

#[Route('/provider')]
class ProviderController extends AbstractController
{

    private ProviderRepository $providerRepository;

    public function __construct(ProviderRepository $providerRepository) {
        $this->providerRepository = $providerRepository;
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

        // Find similar providers according categories & localization
        $similarProviders = $this->providerRepository->findSimilar($provider);

        return $this->render('provider/index.html.twig', [
            'provider' => $provider,
            'similarProviders' => $similarProviders,
        ]);
    }
}
