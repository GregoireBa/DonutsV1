<?php

namespace App\Controller\Front;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart", name="cart_")
 */
class FrontCartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {   
        $panier= $session->get("panier",[]);

        // On "fabrique" les données
        $dataPanier[] = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $produit = $produitRepository->find($id);
            $dataPanier = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $total += $produit->getPrix()*$quantite; 
        }

        return $this->render('front/cart_user.html.twig', ["dataPanier" => $dataPanier,"total" =>$total]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id, SessionInterface $session){
        
        //Récup le panier actuel
        $panier = $session->get("panier",[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1 ;
        }

        //save dans la sesion 
        $session->set("panier",$panier);
        
        return $this->redirectToRoute("cart_index");
    }
}
