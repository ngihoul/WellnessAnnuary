<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCategoryController extends AbstractController
{
    #[Route('/service/category', name: 'service_category')]
    public function index(): Response
    {
        return $this->render('service_category/index.html.twig', [
            'controller_name' => 'ServiceCategoryController',
        ]);
    }
}
