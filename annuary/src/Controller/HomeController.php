<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceCategoryRepository;

class HomeController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private ServiceCategoryRepository $serviceCategoryRepository;

    public function __construct(EntityManagerInterface $entityManager, ServiceCategoryRepository $serviceCategoryRepository) {
        $this->entityManager = $entityManager;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $highlightedCategory = $this->serviceCategoryRepository->getHighlighted();

        // If no category has been highlighted : take first validated category
        if(!$highlightedCategory) {
            $highlightedCategory = $this->serviceCategoryRepository->findOneBy(['validated' => 1]);
        }

        // If more than one category has been highlighted : take the first highlighted category
        if(is_array($highlightedCategory)) {
            $highlightedCategory = $highlightedCategory[0];
        }

        return $this->render('home/index.html.twig', [
            'highlightedCategory' => $highlightedCategory
        ]);
    }
}
