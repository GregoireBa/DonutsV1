<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserupdateMdpType;
use App\Form\UserUpdateEmailType;
use App\Repository\UserRepository;
use App\Form\UserupdateadresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontUserController extends AbstractController
{
    /**
     * @Route("/front/createuser", name="front_create_user")
     */
    public function createuser(Request $request, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
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

    /**
     * @Route("/front/updateuser", name="front_update_user")
     */

    public function userUpdate(
        UserRepository $userRepository
    ) {
        // getUser récupère le user connecté 
        $user_connect = $this->getUser();

        $user_email = $user_connect->getUserIdentifier();

        $user = $userRepository->findOneBy(['email' => $user_email]);

        return $this->render("front/user_form.html.twig", ["user" => $user]);
    }

    /**
     *  @Route("/front/updatemail", name="front_update_user_email")
     */

    public function userEmail(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository
    ) {

        $user_connect = $this->getUser();

        $user_email = $user_connect->getUserIdentifier();

        $user = $userRepository->findOneBy(['email' => $user_email]);

        $userForm = $this->createForm(UserUpdateEmailType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {


            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('front_update_user');
        }

        return $this->render("front/user_update_email.html.twig", ['userEmail' => $userForm->createView()]);
    }

    /**
     *  @Route("/front/updateadresse", name="front_update_user_adresse")
     */

    public function userAdresse(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository
    ) {

        $user_connect = $this->getUser();

        $user_email = $user_connect->getUserIdentifier();

        $user = $userRepository->findOneBy(['email' => $user_email]);

        $userForm = $this->createForm(UserupdateadresseType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('front_update_user');
        }

        return $this->render("front/user_update_adresse.html.twig", ['userAdresse' => $userForm->createView()]);
    }

    /**
     *  @Route("/front/updatemdp", name="front_update_user_mdp")
     */

    public function userMdp(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ) {

        $user_connect = $this->getUser();

        $user_email = $user_connect->getUserIdentifier();

        $user = $userRepository->findOneBy(['email' => $user_email]);

        $userForm = $this->createForm(UserupdateMdpType::class, $user);

        $userForm->handleRequest($request);
        
        if ($userForm->isSubmitted() && $userForm->isValid()){

            $plainPassword = $userForm->get('password')->getData();
            $hashedpasword = $userPasswordHasherInterface->hashPassword($user, $plainPassword);
            $user->setPassword($hashedpasword);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('front_update_user');
        }

        return $this->render("front/user_update_mdp.html.twig", ['userMdp' => $userForm->createView()]);
    }


}
