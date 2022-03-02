<?php

namespace App\Form;

use App\Entity\ServiceCategory;
use App\Repository\ServiceCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $providerId = $options['provider'];

        $builder
            ->add('name', EntityType::class, [
                'label' => 'A quelle catégorie est lié ce stage ?',
                'class' => ServiceCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (ServiceCategoryRepository $repo) use($providerId) {
                    return $repo->getCategoriesNotChosenByProvider($providerId);
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter une catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceCategory::class,
            'provider' => null,
        ]);
    }
}
