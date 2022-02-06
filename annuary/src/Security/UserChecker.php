<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Security\EmailVerifier;

class UserChecker implements UserCheckerInterface
{

    private EntityManagerInterface $entityManager;
    private EmailVerifier $emailVerifier;

    public function __construct(EntityManagerInterface $entityManager, EmailVerifier $emailVerifier) {
        $this->entityManager = $entityManager;
        $this->emailVerifier = $emailVerifier;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if(!$user->isVerified()){
            // Generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ngihoul@hotmail.com', 'Bien-Être'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            throw new CustomUserMessageAuthenticationException("Votre compte n'a pas été verifié. Un nouveau mail de confirmation vous a été envoyé");
        }

        if($user->getBanned()) {
            throw new CustomUserMessageAuthenticationException("Vous êtes banni. Vous ne pouvez donc plus utiliser ce site !");
        }

        if($user->getUnsuccessfulAttempts() == 3) {
            throw new CustomUserMessageAuthenticationException("Votre compte est bloqué suite à 3 tentatives erronées de connexion. Veuillez demander un nouveau mot de passe via le formulaire de connexion.");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if($user->getUnsuccessfulAttempts() > 0) {
            $user->setUnsuccessfulAttempts('0');
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}