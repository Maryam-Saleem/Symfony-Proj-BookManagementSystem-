<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
class LogInFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private RouterInterface $rout;
    use TargetPathTrait;
    public function __construct(RouterInterface $router)
    {
     $this->rout=$router;

    }

    public function authenticate(Request $request): Passport
    {
        $email=$request->request->get('email');
        $password=$request->request->get('password');

        return new Passport(
            new UserBadge($email),
           new PasswordCredentials($password),  [new RememberMeBadge()]);
           /* new CustomCredentials(function($credentials, User $user) {
               return  $credentials==='tada';
            }, $password)
        );*/

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if($target=$this->getTargetPath($request->getSession(),$firewallName))
    {
        return new RedirectResponse($this->rout->generate('app_admin'));
    }
       return new RedirectResponse($this->rout->generate('app_Home_Page'));
    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->rout->generate('app_LogIn');
    }

    //removed things:
    /*public function supports(Request $request): ?bool
    {

        return $request->getPathInfo()==='/logIn' && $request->isMethod("POST");

    }*/
    /*public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

         $request->getSession()->set(Security::AUTHENTICATION_ERROR,$exception);
         return new RedirectResponse($this->rout->generate('app_LogIn'));
    }*/



}
