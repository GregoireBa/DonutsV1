<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;



class AdminProduitController extends AbstractController
{
    /**
     * @Route("/admin/listproduits", name="admin_list_produit")
     */
    public function list_produit(ProduitRepository $produitRepository)
    {
        $produits = $produitRepository->findAll();

        return $this->render("admin/list_produits.html.twig", ["produits" => $produits]);
    }

    /**
     * @Route("/admin/createproduit/",name="admin_create_produit")
     */
    public function createProduit(EntityManagerInterface $entityManagerInterface, Request $request, SluggerInterface $sluggerInterface)
    {
        $produit = new Produit();

        $produitForm = $this->createForm(ProduitType::class, $produit);
        $produitForm->handleRequest($request);

        if ($produitForm->isSubmitted() && $produitForm->isValid()) {
            $produitFile = $produitForm->get('photo')->getData();

            if ($produitFile) {
                $originaleFileName = pathinfo($produitFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluggerInterface->slug($originaleFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $produitFile->guessExtension();

                $produitFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $produit->setPhoto($newFileName);
                $entityManagerInterface->persist($produit);
                $entityManagerInterface->flush();
            }
            return $this->redirectToRoute("admin_list_produit");
        }
        return $this->render("admin/form_produit.html.twig", ["produitForm" => $produitForm->createView()]);
    }

    /**
     * @Route("/admin/updateproduit/{id}", name="admin_update_produit")
     */
    public function updateProduit($id, ProduitRepository $produitRepository, EntityManagerInterface $entityManagerInterface, SluggerInterface $sluggerInterface, Request $request)
    {
        $produit = $produitRepository->find($id);

        $produitForm = $this->createForm(ProduitType::class, $produit);
        $produitForm->handleRequest($request);

        if ($produitForm->isSubmitted() && $produitForm->isValid()) {
            $produitFile = $produitForm->get('photo')->getData();

            if ($produitFile) {
                $originaleFileName = pathinfo($produitFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originaleFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $produitFile->guessExtension();

                $produitFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $produit->setPhoto($newFileName);
            }

            $entityManagerInterface->persist($produit);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_produit");
        }

        return $this->render("admin/form_produit.html.twig", ["produitForm" => $produitForm->createView()]);
    }

    /**
     * @Route("/admin/deleteproduit/{id}", name="admin_delete_produit")
     */
    public function deleteProduit($id,EntityManagerInterface $entityManagerInterface,ProduitRepository $produitRepository){
        $produit = $produitRepository->find($id);

        $entityManagerInterface->remove($produit);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_list_produit");
    }
}
