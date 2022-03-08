<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\UpdateCustomerType;
use App\Service\FileService;
use App\Entity\Customer;
use App\Repository\ProviderRepository;

class CustomerController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    const LOGO_DIRECTORY = 'avatar_directory';

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'customer_profile')]
    public function index(): Response
    {
        if(!$this->getUser() || !$this->isGranted('ROLE_CUSTOMER')) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        $customer = $this->getUser()->getCustomer();

        return $this->render('customer/index.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/profile/update', name: 'customer_update')]
    public function update(Request $request, FileService $fileService): Response
    {
        if(!$this->getUser() && $this->isGranted('ROLE_CUSTOMER')) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        $customer = $this->getUser()->getCustomer();

        $form = $this->createForm(UpdateCustomerType::class, $customer, [
            'postCode' => $customer->getUser()->getLocality()->getPostCode()->getPostCode(),
        ]);

        // Handle form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            // If logo exist, save it to the right directory
            $logo = $form->get('logo')->getData();
            if($logo) {
                try {
                    $logoFileName = $fileService->save($logo, Self::LOGO_DIRECTORY);
                    $customer->setAvatar($logoFileName);
                } catch(FileException $e) {
                    $this->addFlash('error', 'Le fichier n\'a pas pu être enregistré car ' . $e->getMessage());
                }
            }
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return $this->redirectToRoute('customer_profile');
        }

        return $this->renderForm('customer/form.html.twig', [
            'form' => $form,
            'image' => $customer->getAvatar(),
        ]);
    }

    #[Route('/add_favorite/{id}', name: 'customer_add_favorite')]
    public function favorite(ProviderRepository $providerRepository, $id)
    {
        if(!$this->getUser() && $this->isGranted('ROLE_CUSTOMER')) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        $provider = $providerRepository->find($id);
        $customer = $this->getUser()->getCustomer();

        $customer->addFavorite($provider);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $this->redirectToRoute('provider_detail', ['id' => $provider->getId()]);
    }

    #[Route('/delete_favorite/{id}', name: 'customer_delete_favorite')]
    public function delete(Request $request, ProviderRepository $providerRepository, $id)
    {
        if(!$this->getUser() && $this->isGranted('ROLE_CUSTOMER')) {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        $provider = $providerRepository->find($id);
        $customer = $this->getUser()->getCustomer();

        $customer->removeFavorite($provider);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
