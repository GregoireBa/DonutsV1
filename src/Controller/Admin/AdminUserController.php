<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/listusers", name="admin_list_user")
     */
    public function userList(UserRepository $userRepository)
    {
       $users = $userRepository->findAll();

       return $this->render("admin/list_users.html.twig", ["users" => $users]);
    }

    /**
     * @Route("/admin/deleteuser/{id}", name="admin_delete_user")
     */
    public function deleteUser($id,EntityManagerInterface $entityManagerInterface,UserRepository $userRepository){
        $user = $userRepository->find($id);

        $entityManagerInterface->remove($user);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_list_user");
    }
}
