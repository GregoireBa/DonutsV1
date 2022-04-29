<?php

namespace App\Controller\Front;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/cart", name="cart_")
 */
class FrontCartController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session,ProduitRepository $produitRepository){

        $panier = $session->get('panier',[]);
        $panierData = [];
        foreach($panier as $id => $quantity){
            $panierData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantity
            ];
        }
        //dd($panierData);
        return $this->render("base.html.twig", ["panierData"=> $panierData]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id,SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $session->set('panier',$panier);
        dd($session->get('panier'));

    }
}
