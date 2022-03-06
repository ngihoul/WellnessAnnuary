<?php

namespace App\Form;

use App\Entity\Customer;
use App\Form\UpdateUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez votre nom de famille',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Introduisez un nom de famille avec au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez votre prénom',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Introduisez un prénom avec au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('logo', FileType::class, [
                'label' => 'Avatar',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'La taille de l\'avatar ne doit pas dépasser 1Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Le format de l\'avatar  doit être jpg, jpeg, gif ou png',
                    ])
                ],
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'Souhaitez-vous vous inscrire à notre super newsletter ?',
                'required' => false,
            ])
            ->add('user', UpdateUserType::class, [
                'postCode' => $options['postCode'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'postCode' => '',
        ]);
    }
}
