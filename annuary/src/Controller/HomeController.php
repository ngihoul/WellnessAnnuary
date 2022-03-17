<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

use App\Repository\ServiceCategoryRepository;
use App\Repository\ProviderRepository;
use App\Repository\ImageRepository;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ServiceCategoryRepository $serviceCategoryRepository;
    private ProviderRepository $providerRepository;
    private ImageRepository $imageRepository;
    private TagAwareAdapterInterface $cache;

    /**
     * Constructor
     * @param EntityManagerInterface $entityManager
     * @param ServiceCategoryRepository $serviceCategoryRepository
     * @param ProviderRepository $providerRepository
     * @param ImageRepository $imageRepository
     * @param TagAwareAdapterInterface $cache
     */
    public function __construct(EntityManagerInterface $entityManager, ServiceCategoryRepository $serviceCategoryRepository, ProviderRepository $providerRepository, ImageRepository $imageRepository, TagAwareAdapterInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->providerRepository = $providerRepository;
        $this->imageRepository = $imageRepository;
        $this->cache = $cache;
    }

    /**
     * Render homepage
     * @return Response
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Get highlighted Category
        $highlightedCategory = $this->serviceCategoryRepository->getHighlighted();
        // If no category has been highlighted : take first validated category
        if(!$highlightedCategory) {
            $highlightedCategory = $this->serviceCategoryRepository->findOneBy(['validated' => 1]);
        }
        // If more than one category have been highlighted : take the first highlighted category
        if(is_array($highlightedCategory)) {
            $highlightedCategory = $highlightedCategory[0];
        }

        // Get four last subscribers
        $lastSubscribers = $this->providerRepository->getLastSubscribers(0,4);
        // Get images for slider
        $images = $this->imageRepository->getForSlider();

        return $this->render('home/index.html.twig', [
            'highlightedCategory' => $highlightedCategory,
            'lastSubscribers' => $lastSubscribers,
            'images' => $images,
        ]);
    }
}
