<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\TicketReply;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TicketMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail,
    ) {}

    public function notifyAdminNewTicket(Ticket $ticket): void
    {
        $email = (new Email())
            ->from('no-reply@cesizen.fr')
            ->to($this->adminEmail)
            ->subject($ticket->getTitle() . ' — Nouvelle demande')
            ->text("Nouveau ticket créé par {$ticket->getUser()->getEmail()} :\n\n{$ticket->getMessage()}");

        $this->mailer->send($email);
    }

    public function notifyUserNewReply(TicketReply $reply): void
    {
        $ticket = $reply->getTicket();
        $email = (new Email())
            ->from('no-reply@cesizen.fr')
            ->to($ticket->getUser()->getEmail())
            ->subject($ticket->getTitle() . ' — Nouvelle réponse')
            ->text("Une réponse a été ajoutée à votre ticket :\n\n{$reply->getContent()}");

        $this->mailer->send($email);
    }

    public function notifyUserTicketClosed(Ticket $ticket): void
    {
        $email = (new Email())
            ->from('no-reply@cesizen.fr')
            ->to($ticket->getUser()->getEmail())
            ->subject($ticket->getTitle() . ' — Ticket clôturé')
            ->text("Votre ticket a été clôturé par notre équipe.");

        $this->mailer->send($email);
    }
}