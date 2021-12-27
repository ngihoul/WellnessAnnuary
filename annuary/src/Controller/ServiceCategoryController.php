<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceCategoryRepository;

#[Route('/category')]
class ServiceCategoryController extends AbstractController
{
    private ServiceCategoryRepository $serviceCategoryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ServiceCategoryRepository $serviceCategoryRepository, EntityManagerInterface $entityManager) {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'category_index')]
    public function index(): Response
    {
        $categories = $this->serviceCategoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('service_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{category}', name: 'category_detail')]
    public function show($category): Response {
        $category = $this->serviceCategoryRepository->findOneBy(['name' => $category]);

        return $this->render('service_category/detail.html.twig', [
            'category' => $category,
        ]);
    }
}
