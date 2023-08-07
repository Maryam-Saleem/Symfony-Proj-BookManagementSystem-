<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateProfileNameService
{
    private $entityManager;
    private  $security;
    private $validator;

    public function __construct(EntityManagerInterface $entityManagerInterface, Security $security,ValidatorInterface $validator)
 {
 $this->entityManager=$entityManagerInterface;
     $this->security = $security;
     $this->validator=$validator;
 }

 public function updateProfileName(?string $name)

 {
     $user=$this->security->getUser();
     $user->setFirstName($name);
     $errors=$this->validator->validate($user);

     if(count($errors)>0)
     {
         return new Response((string)$errors, 400);
     }
     else{
         $this->entityManager->persist($user);
         $this->entityManager->flush();
     }


 }}