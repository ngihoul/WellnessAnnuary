<?php

namespace App\Controller\Admin;

use App\Entity\Provider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProviderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Provider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Prestataire')
            ->setEntityLabelInPlural('Prestataires')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            AssociationField::new('user', 'Utilisateur')
                ->autocomplete(),
            TextField::new('website', 'Site internet'),
            TextField::new('phoneNumber', 'Téléphone'),
            TextField::new('VTANumber', 'Numéro de TVA'),
            ImageField::new('logo')
                ->setBasePath($this->getParameter('logo_directory'))
                ->setUploadDir('/public/uploads/logo/'),
        ];
    }
}
