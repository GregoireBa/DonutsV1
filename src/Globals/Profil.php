<?php

namespace App\Globals;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Profil extends AbstractController {
    
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {   
        $this->userRepository = $userRepository;
    }

    public function getProfilName(){

        $userCo = $this->getUser()->getUserIdentifier();
        $gProfils =  $this->userRepository->findOneBy(['email' => $userCo])->getName();

        return $gProfils;
    }
}