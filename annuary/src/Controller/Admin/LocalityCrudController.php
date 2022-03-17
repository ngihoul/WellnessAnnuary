<?php

namespace App\Controller\Admin;

use App\Entity\Locality;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LocalityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Locality::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
