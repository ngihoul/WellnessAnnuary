<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ServiceCategory;
use App\Form\ServiceCategoryType;
use App\Repository\ServiceCategoryRepository;
use App\Repository\ProviderRepository;

#[Route('/category')]
class ServiceCategoryController extends AbstractController
{
    const MSG_PAGE_NOT_EXIST = 'Cette page n\'existe pas';

    private ServiceCategoryRepository $serviceCategoryRepository;
    private ProviderRepository $providerRepository;
    private EntityManagerInterface $entityManager;
    private TagAwareAdapterInterface $cache;

    /**
     * Constructor
     * @param ServiceCategoryRepository $serviceCategoryRepository
     * @param ProviderRepository $providerRepository
     * @param EntityManagerInterface $entityManager
     * @param TagAwareAdapterInterface $cache
     */
    public function __construct(ServiceCategoryRepository $serviceCategoryRepository, ProviderRepository $providerRepository, EntityManagerInterface $entityManager, TagAwareAdapterInterface $cache) {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
        $this->providerRepository = $providerRepository;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    /**
     * Renders the list of categories
     * @return Response
     */
    #[Route('/', name: 'category_index')]
    public function index(): Response
    {
        $categories = $this->serviceCategoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('service_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Renders the list of provider for a chosen category
     * @param Request $request
     * @param $categoryName
     * @return Response
     */
    #[Route('/{categoryName}', name: 'category_detail', priority: 0)]
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

    /**
     * Renders & handles form to add a category to a provider
     * @param Request $request
     * @return mixed
     */
    #[Route('/add', name: 'category_add', priority: 1)]
    #[IsGranted('ROLE_PROVIDER')]
    public function add(Request $request): mixed
    {
        // Restrict access to providers only
        if(!$this->isGranted('ROLE_PROVIDER')) {
            $this->addFlash('error', Self::MSG_PAGE_NOT_EXIST);
            return $this->redirectToRoute('home');
        }

        $title = 'Ajouter une catégorie';
        $serviceCategory = new ServiceCategory();
        // Create form
        $form = $this->createForm(ServiceCategoryType::class, $serviceCategory, [
            'provider' => $this->getUser()->getProvider()->getId(),
        ]);
        // Handle form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Fetch the category chosen & the provider
            $serviceCategory = $this->serviceCategoryRepository->find($request->get('service_category')['name']);
            $provider = $this->getUser()->getProvider();
            // Add the category to the provider
            $provider->addServiceCategory($serviceCategory);

            $this->entityManager->persist($provider);
            $this->entityManager->flush();

            $serviceCategoryName = $serviceCategory->getName();
            $this->addFlash('success', "La catégorie de service $serviceCategoryName a été ajoutée");

            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]));
        }

        return $this->renderForm('service_category/form.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }

    #[Route('/delete/{id}', name: 'category_delete', priority: 1)]
    public function delete(ServiceCategory $id) {
        if($this->getUser() && $this->isGranted('ROLE_PROVIDER')) {
            $provider = $this->getUser()->getProvider();
            $serviceCategory = $this->serviceCategoryRepository->find($id);
            $provider->removeServiceCategory($serviceCategory);

            $this->entityManager->persist($provider);
            $this->entityManager->flush();

            $serviceCategoryName = $serviceCategory->getName();
            $this->addFlash('success', "La catégorie de service $serviceCategoryName a été supprimée");

            return $this->redirect($this->generateUrl('provider_detail', ['id' => $provider->getId()]));
        } else {
            $this->addFlash('error', Self::MSG_PAGE_NOT_EXIST);
            return $this->redirectToRoute('home');
        }
    }
}
