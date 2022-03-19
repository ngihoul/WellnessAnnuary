<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\Admin\ServiceCategoryCrudController;
use App\Entity\ServiceCategory;
use App\Entity\Customer;
use App\Entity\User;
use App\Entity\Provider;
use App\Entity\Image;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ServiceCategoryCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bien-Être Admin');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToCrud('Catégories', 'fas fa-list', ServiceCategory::class),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Internautes', 'fas fa-user-secret', Customer::class),
            MenuItem::linkToCrud('Prestataires', 'fas fa-user-tie', Provider::class),
            MenuItem::linkToCrud('Slider', 'fas fa-skiing', Image::class),
            MenuItem::linkToRoute('Retour vers le site', 'fas fa-sign-out-alt', 'home'),
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getEmail());
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(25)
            ->setPaginatorRangeSize(2);
    }
}
