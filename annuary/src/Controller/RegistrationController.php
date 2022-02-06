<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Provider;
use App\Entity\Customer;
use App\Entity\Image;
use App\Form\ProviderType;
use App\Form\CustomerType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register/{typeOfUser}', name: 'registration')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository, SluggerInterface $slugger, $typeOfUser): Response
    {
        // Check whether customer or provider form should be displayed.
        $typeOfUser = strtolower($typeOfUser);

        // $subUser = Provider or Customer Object
        if($typeOfUser == 'provider') {
            $subUser = new Provider();
            $form = $this->createForm(ProviderType::class, $subUser);
            $formTemplate = 'registration/provider_register.html.twig';
            $typeOfImage = 'logo';
        } elseif ($typeOfUser == 'customer') {
            $subUser = new Customer();
            $form = $this->createForm(CustomerType::class, $subUser);
            $formTemplate = 'registration/customer_register.html.twig';
            $typeOfImage = 'avatar';
        } else {
            $this->addFlash('error', 'Cette page n\'existe pas');
            return $this->redirectToRoute('home');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $subUser->getUser();

            // Check if email already used by a registered user in DB
            $userExist = $userRepository->findOneBy(['email' => $user->getEmail()]);

            // Redirect user to forgotten password page if email already exists in DB
            if($userExist) {
                $this->addFlash('error', 'Il semble que vous avez déjà un compte.');

                return $this->redirectToRoute('home');
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

            // Save logo in DB
            $logo = $form->get('logo')->getData();

            $originalFileName = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFileName = $slugger->slug($originalFileName);
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $logo->guessExtension();

            try {
                $logo->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );
            } catch (FileException $e) {
                dd('Impossible de sauver image ' . $e);
            }

            // Create Image Object
            $image = New Image();
            $image->setType($typeOfImage);
            $image->setFileName($newFileName);

            $subUser->addImage($image);

            // Save data in DB
            $entityManager->persist($subUser);
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

        return $this->renderForm($formTemplate, [
            'form' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            $this->addFlash('error', 'Ce lien n\'est pas correct. Veuillez vous inscrire.');
            return $this->redirectToRoute('registration', ['typeOfUser' => 'customer']);
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            $this->addFlash('error', 'Ce lien n\'est pas correct. Veuillez vous inscrire.');
            return $this->redirectToRoute('registration', ['typeOfUser' => 'customer']);
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('registration', ['typeOfUser' => 'customer']);
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre compte a été validé. Vous pouvez vous connecter.');

        return $this->redirectToRoute('home');
    }
}
