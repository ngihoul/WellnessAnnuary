<?php

namespace App\Controller\Admin;

use App\Entity\ServiceCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ServiceCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Catégorie de service')
        ->setEntityLabelInPlural('Catégories de service')
        ->setDefaultSort(['highlighted' => 'DESC', 'name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            BooleanField::new('highlighted', 'Catégorie du mois ?'),
            BooleanField::new('validated', 'Validée ?'),
            ImageField::new('image', 'Image de présentation')
                ->setBasePath($this->getParameter('category_directory'))
                ->setUploadDir('/public/uploads/category/')
                ->setUploadedFileNamePattern(
                    fn (UploadedFile $file): string => sprintf('%s.%s', $file->getFilename(), uniqid())
),
        ];
    }
}
