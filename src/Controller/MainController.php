<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
     /**
     * @Route("/main", name="app_main")
     */
    public function produitsList(ProduitRepository $produitsRepository){
        $produits = $produitsRepository->findAll();

        return $this->render("home.html.twig", ['produits' => $produits]);
    }
}
