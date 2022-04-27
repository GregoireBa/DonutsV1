<?php

namespace App\Controller\Front;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCommandeController extends AbstractController
{
    /**
     * @Route("/front/createcommande", name="front_create_commande")
     */
    public function createCommande(EntityManagerInterface $entityManagerInterface, Request $request, UserRepository $userRepository,ProduitRepository $produitRepository)
    {
        $commande = new Commande();
        $commandeForm = $this->createForm(CommandeType::class);



        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {
            //Récupération du user connecter et ajout du user_Id dans l'objet commande
            $userCo = $this->getUser()->getUserIdentifier();
            $userData =  $userRepository->findOneBy(['email' => $userCo]);
            $commande->setUser($userData->getId());

            //initialisation et recuperation de la date a laquelle la commande est faite
            $dateEnregistrement = new \DateTime();
            $dateEnregistrement->getTimestamp();
            $commande->setDateEnregistrement($dateEnregistrement);
        }
    }
}
