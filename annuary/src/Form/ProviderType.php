<?php

namespace App\Form;

use App\Entity\Provider;
use App\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez au moins 2 carectères',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Introduisez un nom de société avec au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('website', UrlType::class, [

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
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('user', UserType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
