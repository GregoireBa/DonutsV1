<?php

namespace App\Globals;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart extends AbstractController
{

    private $produitRepository;
    private $session;

    public function __construct(ProduitRepository $produitRepository, SessionInterface $session)
    {
        $this->produitRepository = $produitRepository;
        $this->session = $session;
    }

    public function showCart()
    {

        $panier = $this->session->get('panier', []);
        $gPanierData = [];
        foreach ($panier as $id => $quantity) {
            $gPanierData[] = [
                'produit' => $this->produitRepository->find($id),
                'quantite' => $quantity
            ];
        }

        $total = 0;
        foreach ($gPanierData as $item) {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
            //$gPanierData = ['total' => $total];
        }
        return $gPanierData;
    }
}
