<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UpdateProfileNameService;

class FormsController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, UpdateProfileNameService $updateProfileNameService): Response
    {
        if(!$this->isGranted("IS_AUTHENTICATED_REMEMBERED"))
        {
            throw $this->createAccessDeniedException(("You have no access to user page"));
        }
        
        $user=new User();
        $user=$user->getFirstName();
        $form=$this->createForm(ProfileType::Class,$user );
        $form->handleRequest($request);
        
            if($form->isSubmitted() && $form->isValid())
            {
                $userName=$form["firstName"]->getData();
                //$userName=$request->request->get('ProfileType');
                $updateProfileNameService->updateProfileName($userName);
                return $this->redirectToRoute('app_Home_Page');
            }
            
        return $this->render('forms/profileForm.html.twig', [
          'form'=>$form->CreateView(),
            'controller_name'=>'app_profile'
        ]);

    }

    #[Route('/userUpdated', name: 'app_User_Updated')]
    public function userUpdate(Request $request):Response
    {
        return new Response("Submitted");
    }


}
