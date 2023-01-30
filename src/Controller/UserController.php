<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    #[Route('/users/new', name: 'app_user_add', methods: ['POST', 'GET'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_users');
        }
        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/user/edit{id}', name: 'app_user_edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $manager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['GET'])]
    public function delete(ManagerRegistry $doctrine, User $user): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès !');

        return $this->redirectToRoute('app_users');
    }
}
