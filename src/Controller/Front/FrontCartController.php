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
        //dd($session->get('panier'));
        return $this->redirectToRoute('app_main');

    }
}
