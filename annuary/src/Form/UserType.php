<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Locality;
use App\Entity\PostCode;
use App\Entity\Municipality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez une adresse email',
                    ]),
                    new Length([
                        'min' => 7,
                        'max' => 180,
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Votre mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un mot de passe',
                    ]),
                    new Length([
                        'min' => 7,
                        'minMessage' => 'Choisissez un mot de passe avec au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmer votre mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez une confirmation de mot de passe',
                    ]),
                    new Length([
                        'min' => 7,
                        'minMessage' => 'Choisissez un mot de passe avec au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('addressStreet', TextType::class, [
                'label' => 'Rue',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un nom de rue',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('addressNumber', TextType::class, [
                'label' => 'Numéro',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un numéro de rue',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 10,
                    ]),
                ],
            ])
            ->add('locality', EntityType::class, [
                'label' => 'Localité',
                'class' => Locality::class,
                'choice_label' => 'name'
            ])
            ->add('postCode', TextType::class, [
                'label' => 'Code postal',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduisez un code postal',
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 16,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Direction le paradis !',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
