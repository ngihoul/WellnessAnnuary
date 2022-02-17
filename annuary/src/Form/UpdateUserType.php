<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Locality;
use App\Entity\PostCode;
use App\Entity\Municipality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserType extends AbstractType
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
                'data' => $options['postCode'],
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
            'postCode' => '',
        ]);
    }
}
