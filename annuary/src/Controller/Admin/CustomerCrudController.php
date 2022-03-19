<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Internaute')
            ->setEntityLabelInPlural('Internautes')
            ->setDefaultSort(['lastName' => 'ASC', 'firstName' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('lastName', 'Nom'),
            TextField::new('firstName', 'Prénom'),
            AssociationField::new('user', 'Utilisateur')
                ->autocomplete(),
            BooleanField::new('newsletter', 'Inscrit à newsletter ?'),
            ImageField::new('avatar')
                ->setBasePath($this->getParameter('avatar_directory'))
                ->setUploadDir('/public/uploads/category/'),
        ];
    }
}
