<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceCategoryRepository;
use App\Repository\ProviderRepository;

#[Route('/category')]
class ServiceCategoryController extends AbstractController
{
    private ServiceCategoryRepository $serviceCategoryRepository;
    private ProviderRepository $providerRepository;
    private EntityManagerInterface $entityManager;
    private TagAwareAdapterInterface $cache;

    public function __construct(ServiceCategoryRepository $serviceCategoryRepository, ProviderRepository $providerRepository, EntityManagerInterface $entityManager, TagAwareAdapterInterface $cache) {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->providerRepository = $providerRepository;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    #[Route('/', name: 'category_index')]
    public function index(): Response
    {
        $categories = $this->serviceCategoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('service_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{categoryName}', name: 'category_detail')]
    public function show(Request $request, $categoryName): Response
    {
        // Fetch data of selected category
        $category = $this->serviceCategoryRepository->findOneBy(['name' => $categoryName]);

        // Paginator for providers in this category
        $offset = max(0, $request->query->getInt('offset', 0));
        $providers = $this->providerRepository->findByCategory($category, $offset);

        if(!$category) {
            $this->addFlash('error', "La catégorie <em>\"$categoryName\"</em> n'existe pas");

            return $this->redirectToRoute('home');
        }

        return $this->render('service_category/detail.html.twig', [
            'category' => $category,
            'providers' => $providers,
            'previous' => $offset - ProviderRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($providers), $offset + ProviderRepository::PAGINATOR_PER_PAGE)
        ]);
    }
}
