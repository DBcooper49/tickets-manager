<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ticket;
use App\Form\TicketType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\TicketRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    #[Route('/tickets', name: 'app_tickets')]
    public function index(TicketRepository $repository, UserRepository $user): Response
    {
        $users = $user->findAll();
        $tickets = $repository->findAll();
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
            'tickets' => $tickets,
            'users' => $users
        ]);
    }

    #[Route('/tickets/new', name: 'app_ticket_add', methods: ['POST', 'GET'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $ticket->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($ticket);
            $manager->flush();
            return $this->redirectToRoute('app_tickets');
        }

        return $this->render('ticket/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/ticket/edit{id}', name: 'app_ticket_edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $manager = $doctrine->getManager();
        $ticket = $doctrine->getRepository(Ticket::class)->find($id);
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $manager->persist($ticket);
            $manager->flush();
            return $this->redirectToRoute('app_tickets');
        }

        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ticket/delete/{id}', name: 'app_ticket_delete', methods: ['GET'])]
    public function delete(ManagerRegistry $doctrine, Ticket $ticket): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($ticket);
        $manager->flush();

        $this->addFlash('success', 'Ticket supprimé avec succès !');

        return $this->redirectToRoute('app_tickets');
    }

    #[Route('/ticket/assignment/{id}/{idUser}', name: 'app_ticket_assignment', methods: ['GET'])]
    public function assignToUser(ManagerRegistry $doctrine, Request $request, Ticket $ticket, User $user): Response
    {
        $userId = $user->getId();
        $manager = $doctrine->getManager();

        if ($request->isXmlHttpRequest()) {
            $ticket->setAssignedTo($userId);
            $manager->persist($ticket);
            $manager->flush();

            return $this->redirectToRoute('app_tickets');
        }
        return $this->redirectToRoute('app_tickets');
    }
}
