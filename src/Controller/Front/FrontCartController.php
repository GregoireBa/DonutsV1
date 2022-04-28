<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/card", name="card_")
 */
class FrontCartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('front_cart/index.html.twig', [
            'controller_name' => 'FrontCartController',
        ]);
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

        dd($session);
    }
}