<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontUserController extends AbstractController
{
    /**
     * @Route("/front/createuser", name="front_create_user")
     */
    public function createuser(Request $request,EntityManagerInterface $entityManagerInterface,UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class,$user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted()&& $userForm->isValid()){
            $user->setRoles(["ROLE_USER"]);

            // On récupère le password entré dans le formulaire.
            $plainpassword = $userForm->get('password')->getData();

            // Hashage du password
            $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $plainpassword);
            $user->setPassword($hashedPassword);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_login');

        }

        return $this->render('front/inscription.html.twig', ['userForm' => $userForm->createView()]);
    }
}
