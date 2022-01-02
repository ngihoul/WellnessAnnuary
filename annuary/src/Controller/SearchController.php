<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProviderRepository;

class SearchController extends AbstractController
{
    /**
     * Display Search results
     * @param Request $request
     * @return Response
     */
    #[Route('/search', name: 'search', methods:["GET"])]
    public function search(Request $request, ProviderRepository $providerRepository): Response
    {
        // Get all search parameters
        $what = $request->get('q');
        $whichCategory = $request->get('c');
        $where = $request->get('w');

        $results = $providerRepository->findBySearch($what, $whichCategory, $where);

        return $this->render('search/index.html.twig', [
            'what' => $what,
            'whichCategory' => $whichCategory,
            'where' => $where,
            'results' => $results
        ]);
    }
}
