<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceCategoryRepository;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/category')]
class ServiceCategoryController extends AbstractController
{
    private ServiceCategoryRepository $serviceCategoryRepository;
    private EntityManagerInterface $entityManager;
    private TagAwareAdapterInterface $cache;

    public function __construct(ServiceCategoryRepository $serviceCategoryRepository, EntityManagerInterface $entityManager, TagAwareAdapterInterface $cache) {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
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
    public function show($categoryName): Response
    {
        // ?? Impossible to cache this data => $category is null
        $category = $this->serviceCategoryRepository->findOneBy(['name' => $categoryName]);

        if(!$category) {
            $this->addFlash('error', "La catégorie <em>\"$categoryName\"</em> n'existe pas");

            return $this->redirectToRoute('home');
        }

        return $this->render('service_category/detail.html.twig', [
            'category' => $category,
        ]);
    }
}
