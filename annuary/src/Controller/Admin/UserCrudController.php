<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setDefaultSort(['email' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            ChoiceField::new('roles', 'Rôles')
            ->allowMultipleChoices()
            ->setChoices(['Administrateur' => 'ROLE_ADMIN', 'Prestataire' => 'ROLE_PROVIDER', 'Internaute' => 'ROLE_CUSTOMER']),
            TextField::new('addressStreet', 'Rue'),
            TextField::new('addressNumber','Numéro adresse'),
            AssociationField::new('locality', 'Localité')
                ->autocomplete(),
            DateTimeField::new('registeredOn', 'Inscrit le')
                ->setFormat('dd/MM/yyyy HH:mm'),
            IntegerField::new('unsuccessfulAttempts', 'Tentative de connexion'),
            BooleanField::new('banned', 'Banni ?'),
            BooleanField::new('isVerified', 'Vérifié ?'),
        ];
    }
}
