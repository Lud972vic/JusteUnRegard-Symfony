<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/easy-admin",name="easy_admin")
     */
    public function easyAdmin()
    {
        return $this->redirectToRoute('easyadmin');
    }

    /**
     * Liste des utilisateurs du site
     * @Route("/users-list",name="users_list")
     * Injection de dépendance -> UserRepository $userRepository et on a accès à toutes les méthodes du repository
     */
    public function usersList(UserRepository $userRepository)
    {
        return $this->render('admin/adminUsersList.html.twig', [
            'usersList' => $userRepository->findAll(),
        ]);
    }

    /**
     * Modifier un utilisateur
     * @Route("/user-edit/{id}",name="user_edit")
     */
    public function userEdit(User $user, Request $request)
    {
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'User successfully edited !');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/adminUserEdit.html.twig', [
            'userEditFormType' => $form->createView(),
        ]);
    }
}
