<?php

namespace App\Form;

use App\Entity\Internship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InternshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du stage',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez Introduire un nom de stage',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Veuillez introduire un nom de stage d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez introduire une description',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Veuillez introduire une description d\'au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('additionalInformation', TextareaType::class, [
                'label' => 'Information complémentaire',
                'required' => false,
            ])
            ->add('startAt', DateType::class, [
                'label' => 'Début du stage',
                'widget' => 'single_text',
            ])
            ->add('endAt', DateType::class, [
                'label' => 'Fin du stage',
                'widget' => 'single_text',
            ])
            ->add('displayedFrom', DateType::class, [
                'label' => 'Affiché à partir du',
                'widget' => 'single_text',
            ])
            // Must be equal to endAt.
            // ->add('displayedUntil')
            // $this->getUser()
            // ->add('provider')
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Internship::class,
            'submit_label' => 'Ajouter un stage',
        ]);
    }
}
