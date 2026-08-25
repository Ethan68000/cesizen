<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\TicketReply;
use App\Form\TicketReplyType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use App\Service\TicketMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tickets')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class TicketController extends AbstractController
{
    public function __construct(
        private RateLimiterFactory $ticketCreationLimiter,
    ) {}

    #[Route('', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        $tickets = $this->isGranted('ROLE_ADMIN')
            ? $ticketRepository->findVisibleForAdmin()
            : $ticketRepository->findVisibleForUser($this->getUser());

        return $this->render('ticket/index.html.twig', ['tickets' => $tickets]);
    }

    #[Route('/nouveau', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, TicketMailer $mailer): Response
    {
        $ticket = new Ticket();
        $ticket->setUser($this->getUser());

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $limiter = $this->ticketCreationLimiter->create((string) $this->getUser()->getId());
            if (!$limiter->consume(1)->isAccepted()) {
                $this->addFlash('error', 'Vous devez attendre 10 minutes entre deux demandes.');
                return $this->redirectToRoute('app_ticket_new');
            }

            $em->persist($ticket);
            $em->flush();

            $mailer->notifyAdminNewTicket($ticket);

            $this->addFlash('success', 'Votre demande a bien été envoyée.');
            return $this->redirectToRoute('app_ticket_index');
        }

        return $this->render('ticket/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET', 'POST'])]
    public function show(Ticket $ticket, Request $request, EntityManagerInterface $em, TicketMailer $mailer): Response
    {
        $this->denyAccessUnlessGranted('TICKET_VIEW', $ticket);

        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $reply = new TicketReply();
        $form = $this->createForm(TicketReplyType::class, $reply);
        $form->handleRequest($request);

        if (!$ticket->isEstCloture() && $form->isSubmitted() && $form->isValid()) {
            $reply->setAuthor($this->getUser());
            $reply->setIsFromAdmin($isAdmin);
            $ticket->addReply($reply);
            $em->flush();

            if ($isAdmin) {
                $mailer->notifyUserNewReply($reply);
            } else {
                $mailer->notifyAdminNewTicket($ticket);
            }

            return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
            'is_admin' => $isAdmin,
        ]);
    }

    #[Route('/{id}/cloturer', name: 'app_ticket_close', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function close(Ticket $ticket, Request $request, EntityManagerInterface $em, TicketMailer $mailer): Response
    {
        if ($this->isCsrfTokenValid('close_ticket_' . $ticket->getId(), $request->getPayload()->getString('_token'))) {
            $ticket->setEstCloture(true);
            $em->flush();
            $mailer->notifyUserTicketClosed($ticket);
            $this->addFlash('success', $ticket->getTitle() . ' a été clôturé.');
        }

        return $this->redirectToRoute('app_ticket_show', ['id' => $ticket->getId()]);
    }
}