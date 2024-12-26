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

    public function authenticate(Request $request): Passport
    {
        // Use $request->request to get form parameters
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');
    
        // Store the last username in the session (for showing it after a failed login attempt)
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
    
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser(); 
    
        if ($user instanceof User) {
            $roles = $user->getRoles();
             // If user has the 'ROLE_ADMIN', redirect to 'app_home'
            if (in_array('ROLE_ADMIN', $roles)) {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            }
    
            // If user has the 'ROLE_ENSEIGNANT', redirect to 'app_reserve_index'
            if (in_array('ROLE_ENSEIGNANT', $roles)) {
                return new RedirectResponse($this->urlGenerator->generate('app_reserve_index'));
            }
    
            // If user has the 'ROLE_ETUDIANT', redirect to 'app_reserve_index' with a filter for their promotion
            if (in_array('ROLE_ETUDIANT', $roles)) {
                // Get the user's promotion
                $promotion = $user->getPromotion();
                if ($promotion) {
                    // Redirect to the 'app_reserve_index' route with a 'promotion_id' filter
                    return new RedirectResponse($this->urlGenerator->generate('app_reserve_index', [
                        'promotion_id' => $promotion->getId(),
                    ]));
                }
            }
        }    
       
    
        // Default fallback if no roles matched (it should not hit this point)
        return new RedirectResponse($this->urlGenerator->generate('app_default_dashboard'));
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
