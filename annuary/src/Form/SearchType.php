<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Controller\SearchController;
use App\Entity\ServiceCategory;
use App\Repository\ServiceCategoryRepository;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction('/search')
            ->setMethod('GET')
            ->add('q', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                'label' => 'Que cherchez-vous ?',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Nom d\'un prestataire',
                    'autocomplete' => 'off'
                ],
                'required' => false
            ])
            ->add('c', EntityType::class, [
                'class' => ServiceCategory::class,
                'choice_label' => 'name',
                // Sort list by category name
                'query_builder' => function (ServiceCategoryRepository $repo) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'label' => 'Dans quelle categorie ?',
                'required' => false,
                'placeholder' => 'Toutes les catégories'
            ])
            ->add('w', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                'label' => 'Où le cherchez-vous ?',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Ville, code postal, localité, ...',
                    'autocomplete' => 'off',
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Evadez-vous !',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
