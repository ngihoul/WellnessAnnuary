<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceCategoryRepository;
use App\Repository\ProviderRepository;
use App\Repository\ImageRepository;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private ServiceCategoryRepository $serviceCategoryRepository;
    private ProviderRepository $providerRepository;
    private ImageRepository $imageRepository;
    private TagAwareAdapterInterface $cache;

    public function __construct(EntityManagerInterface $entityManager, ServiceCategoryRepository $serviceCategoryRepository, ProviderRepository $providerRepository, ImageRepository $imageRepository, TagAwareAdapterInterface $cache) {
        $this->entityManager = $entityManager;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->providerRepository = $providerRepository;
        $this->imageRepository = $imageRepository;
        $this->cache = $cache;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $highlightedCategory = $this->cache->get('categoryOfTheMonth', function(ItemInterface $item) {

            $item->tag(['category']);

            $highlightedCategory = $this->serviceCategoryRepository->getHighlighted();

            // If no category has been highlighted : take first validated category
            if(!$highlightedCategory) {
                $highlightedCategory = $this->serviceCategoryRepository->findOneBy(['validated' => 1]);
            }

            // If more than one category have been highlighted : take the first highlighted category
            if(is_array($highlightedCategory)) {
                $highlightedCategory = $highlightedCategory[0];
            }

            return $highlightedCategory;

        });

        // $lastSubscribers = $this->cache->get('lastSubscribers', function(ItemInterface $item) {

            // $item->tag(['provider']);

        $lastSubscribers = $this->providerRepository->getLastSubscribers(0,4);

        // });

        $images = $this->imageRepository->getForSlider();


        return $this->render('home/index.html.twig', [
            'highlightedCategory' => $highlightedCategory,
            'lastSubscribers' => $lastSubscribers,
            'images' => $images,
        ]);
    }
}
