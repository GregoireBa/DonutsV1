<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
     /**
     * @Route("/main", name="app_main")
     */
    public function produitsList(
        ProduitRepository $produitsRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository
        ){
        $produits = $produitsRepository->findAll();

        if($this->getUser()){
        $user_connect = $this->getUser();
        
        $user_email = $user_connect->getUserIdentifier();
        
        $user = $userRepository->findOneBy(['email' => $user_email]);
        
        $id = $user->getId();
        
        $user = $userRepository->find($id);
        return $this->render("home.html.twig", ['produits' => $produits, 'user' => $user] );
        }

        return $this->render("home.html.twig", ['produits' => $produits]);
        // return $this->render("home.html.twig", ['user' => $user] );

    }

    // public function userCo(
    //     Request $request,
    //     EntityManagerInterface $entityManagerInterface,
    //     UserRepository $userRepository)
    // {
    //     $user_connect = $this->getUser();

    //     $user_email = $user_connect->getUserIdentifier();

    //     $user = $userRepository->findOneBy(['email' => $user_email]);

    //     $id = $user->getId();

    //     $user = $userRepository->find($id);

    //     return $this->render("home.html.twig", ['user' => $user]);
    // }
}
