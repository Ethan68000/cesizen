<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/tickets')]
#[IsGranted('ROLE_ADMIN')]
class TicketController extends AbstractController
{
    #[Route('', name: 'admin_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('admin/ticket/index.html.twig', [
            'tickets' => $ticketRepository->findVisibleForAdmin(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_ticket_delete', methods: ['POST'])]
    public function delete(Ticket $ticket, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_ticket_' . $ticket->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($ticket);
            $em->flush();
            $this->addFlash('success', 'Ticket supprimé.');
        }

        return $this->redirectToRoute('admin_ticket_index');
    }
}