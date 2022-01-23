<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Entity\User;
use App\Form\ProviderType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register/provider', name: 'registration_provider')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $provider->getUser();

            // Check if email already used by a registered user in DB
            $userExist = $userRepository->findOneBy(['email' => $user->getEmail()]);

            // Redirect user to forgotten password page if email already exists in DB
            if($userExist) {
                $this->addFlash('error', 'Il semble vous avez déjà un compte.');

                // TODO : redirect to forgotten password page
                return $this->render('registration/provider_register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Check if password confirmation is similar to password
            $password = $form->get('user')->get('password')->getData();
            $confirmPassword = $form->get('user')->get('confirmPassword')->getData();

            if($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe doivent être identiques.');

                return $this->render('registration/provider_register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('user')->get('password')->getData()
                )
            );

            // Save data in DB
            $entityManager->persist($provider);
            $entityManager->flush();

            // Generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ngihoul@hotmail.com', 'Bien-Être'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Vous êtes bientôt au paradis ! Vérifiez vos mails et confirmez votre adresse mail avant de vous connecter.');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/provider_register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('registration_provider');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('registration_provider');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('registration_provider');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('registration_provider');
    }
}
