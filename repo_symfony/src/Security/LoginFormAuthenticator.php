<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // Gére l'authentification
    public function authenticate(Request $request): Passport
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $csrfToken = $request->get('_csrf_token');

        // on garde email en cas d'échec de connexion
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Pour débug
        dump($request->request->all());

        return new Passport(
            new UserBadge($email), // Vérifie si le mail existe
            new PasswordCredentials($password), // Vérifie le mdp
            [
                new CsrfTokenBadge('authenticate', $csrfToken), // Vérifie le token CSRF
                new RememberMeBadge(), //Pour se souvenir de l'user
            ]
        );
    }

    // Fonction en cas de réussite de l'authentification
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // On récupère les rôles de l'utilisateur
        $user = $token->getUser();
        if ($user instanceof User) {
            $roles = $user->getRoles();

            // Redirection selon les rôles
            if (in_array('ROLE_USER', $roles)) { // Admin
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            }

            if (in_array('ROLE_ENSEIGNANT', $roles)) { // Enseignant
                return new RedirectResponse($this->urlGenerator->generate('app_reserve_index'));
            }

            if (in_array('ROLE_ETUDIANT', $roles)) { // Étudiant
                return new RedirectResponse($this->urlGenerator->generate('app_etudiant_dashboard'));
            }

            if (in_array('ROLE_AGENT', $roles)) { // Agent universitaire
                return new RedirectResponse($this->urlGenerator->generate('app_agent_dashboard'));
            }
        }

        // Redirection par défaut si aucun rôle ne correspond
        return new RedirectResponse($this->urlGenerator->generate('app_default_dashboard'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
