<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProviderRepository;
use App\Repository\LocalityRepository;
use App\Form\SearchType;

class SearchController extends AbstractController
{
    public function displayForm() {
        $form = $this->createForm(SearchType::class);

        return $this->renderForm('fragments/_searchForm.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Display Search results
     * @param ProviderRepository $providerRepository
     * @return Response
     */
    #[Route('/search', methods:["GET"])]
    public function search(Request $request, ProviderRepository $providerRepository): Response
    {

        $query = $request->get('search');

        // Get all search parameters
        $what = $query['q'];
        $whichCategory = $query['c'];
        $where = $query['w'];

        $results = $providerRepository->findBySearch($what, $whichCategory, $where);

        return $this->render('search/index.html.twig', [
            'what' => $what,
            'whichCategory' => $whichCategory,
            'where' => $where,
            'results' => $results
        ]);
    }

    /**
     * API for autocomplete
     */
    #[Route('/search/what/', name: 'search_what')]
    public function what(Request $request, ProviderRepository $providerRepository) {

        $what = trim($request->get('q'));

        $results = $providerRepository->findForAutoCompletion($what);

        return new JsonResponse($results);
    }

    #[Route('/search/where/', name: 'search_where')]
    public function where(Request $request, LocalityRepository $localityRepository) {

        $where = trim($request->get('w'));

        $results = $localityRepository->findForAutoCompletion($where);

        return new JsonResponse($results);
    }
}
