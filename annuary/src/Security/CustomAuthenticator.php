<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UserRepository;

class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private const LOGIN_ROUTE = 'login';

    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        //    return new RedirectResponse($targetPath);
        // }

        $request->getSession()->getFlashBag()->add('success', 'Vous êtes connecté.<br>Bienvenue au paradis !');
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception, ): Response
    {
        $email = $request->request->get('email', '');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if($user) {
            $unsuccessfulAttempts = $user->getUnsuccessfulAttempts();

            if($unsuccessfulAttempts < 3) {
                $user->setUnsuccessfulAttempts($unsuccessfulAttempts+ 1);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }

        return parent::onAuthenticationFailure($request, $exception); // TODO: Change the autogenerated stub
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
