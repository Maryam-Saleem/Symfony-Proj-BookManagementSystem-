<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/logIn', name: 'app_LogIn')]
    public function LogIn(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('Security/LogInForm.html.twig',
        [
            'error'=>$authenticationUtils->getLastAuthenticationError(),
            'message'=>"LogInPage"
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/logout', name: 'app_LogOut')]
    public function LogOut()
    {
        throw new Exception('logout() should never be reached');
    }

    #[Route('/', name: 'app_Home_Page')]
 public function HomePage(Request $request):Response
 {
     $request->getSession()->getName();
    return $this->render('Security/homePage.html.twig',['message'=>'Home Page']);
  }



    #[Route('/admin', name:'app_admin')]
 public function admin():Response
  {
      if(!$this->isGranted("ROLE_ADMIN"))
      {
          throw $this->createAccessDeniedException("You have no access to admin Page");
      }

      return $this->render('Security/AdminPage.html.twig',['message'=>'Admin Space']);
  }

    #[Route('/user', name:'app_user')]
    public function User():Response
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        return $this->render('Security/UserPage.html.twig',['message'=>'User Space']);
    }




}
