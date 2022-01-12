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
     * @param Request $request
     * @param ProviderRepository $providerRepository
     * @return Response
     */
    #[Route('/search', name: 'search', methods:["GET"])]
    public function search(Request $request, ProviderRepository $providerRepository): Response
    {

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $what = $form->getData()['q'];
            $whichCategory = $form->getData()['c'];
            $where = $form->getData()['w'];

            $offset = max(0, $request->query->getInt('offset', 0));
            $providers = $providerRepository->findBySearch($what, $whichCategory, $where, $offset);

            return $this->render('search/results.html.twig', [
                'what' => $what,
                'whichCategory' => $whichCategory,
                'where' => $where,
                'providers' => $providers,
                'previous' => $offset - ProviderRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($providers), $offset + ProviderRepository::PAGINATOR_PER_PAGE)
            ]);
        }

        $this->addFlash('error', 'Recherche incorrecte. Veuillez utiliser le formulaire.');

        return $this->redirectToRoute('home');
    }

    /**
     * API : Autocompletion of WHAT field
     * @param Request $request
     * @param ProviderRepository $providerRepository
     * @return JsonResponse
     */
    #[Route('/search/what/', name: 'search_what')]
    public function what(Request $request, ProviderRepository $providerRepository) {

        $what = trim($request->get('q'));

        $results = $providerRepository->findForAutoCompletion($what);

        return new JsonResponse($results);
    }

    /**
     * API : Autocompletion for WHERE field
     * @param Request $request
     * @param LocalityRepository $localityRepository
     * @return JsonResponse
     */
    #[Route('/search/where/', name: 'search_where')]
    public function where(Request $request, LocalityRepository $localityRepository) {

        $where = trim($request->get('w'));

        $results = $localityRepository->findForAutoCompletion($where);

        return new JsonResponse($results);
    }
}
