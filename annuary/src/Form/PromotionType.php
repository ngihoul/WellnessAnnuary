<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\Provider;
use App\Entity\ServiceCategory;
use App\Repository\ServiceCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $providerId = $options['provider'];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un nom de stage',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Introduisez un nom de stage avec au moins {{ limit }} caractères',
                        'max' => 255,
                        'maxMessage' => 'Le nombre maximum de caractère est de {{ limit }}'
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('PDFDocument', FileType::class, [
                'label' => 'Document PDF',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'La taille du document PDF ne doit pas dépasser 1Mo',
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Le format du document doit être *.pdf',
                    ])
                ],
            ])
            ->add('startAt', DateType::class, [
                'label' => 'Début de la promotion',
                'widget' => 'single_text',
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Fin de la promotion',
                'widget' => 'single_text',
            ])
            ->add('displayedFrom', DateType::class, [
                'label' => 'Publiée à partir de',
                'widget' => 'single_text',
            ])
            ->add('displayedUntil', DateType::class, [
                'label' => 'Publiée jusque',
                'widget' => 'single_text',
            ])
            ->add('serviceCategory', EntityType::class, [
                'label' => 'A quelle catégorie est lié ce stage ?',
                'class' => ServiceCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (ServiceCategoryRepository $repo) use($providerId) {
                    return $repo->createQueryBuilder('s')
                        ->join('s.providers', 'p')
                        ->andWhere('p.id = :providerId')
                        ->setParameter(':providerId', $providerId)
                        ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'submit_label' => 'Ajouter une promotion',
            'provider' => null,
        ]);
    }
}
