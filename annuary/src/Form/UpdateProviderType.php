<?php

namespace App\Form;

use App\Entity\Provider;
use App\Form\UpdateUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un nom de société',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Introduisez un nom de société avec au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'La taille du logo ne doit pas dépasser 1Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Le format du logo doit être jpg, jpeg, gif ou png',
                    ])
                ],
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site internet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez une adresse internet',
                    ]),
                    new Length([
                        'min' => 9,
                        'minMessage' => 'Introduisez une adresse internet avec au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un numéro de téléphone valable',
                    ]),
                    new Length([
                        // Min for local phone number in Belgium
                        'min' => 9,
                        'minMessage' => 'Introduisez un numéro avec au moins {{ limit }} caractères',
                        // Maximum for longest phone number in the world
                        'max' => 25,
                    ]),
                ],
            ])
            ->add('VTANumber', TextType::class, [
                'label' => 'Numéro de TVA',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un numéro de TVA valable',
                    ]),
                    new Length([
                        // Min for VTA in Belgium
                        'min' => 10,
                        'minMessage' => 'Introduisez un numéro TVA avec au moins {{ limit }} caractères',
                        // Maximum for longest VTA number in the world
                        'max' => 15,
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
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
            'data_class' => Provider::class,
            'postCode' => '',
        ]);
    }
}
