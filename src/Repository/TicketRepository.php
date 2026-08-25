<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findVisibleForUser(User $user): array
    {
        $cutoff = new \DateTimeImmutable('-2 days');

        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.estCloture = false OR t.closedAt > :cutoff')
            ->setParameter('user', $user)
            ->setParameter('cutoff', $cutoff)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findVisibleForAdmin(): array
    {
        $cutoff = new \DateTimeImmutable('-2 days');

        return $this->createQueryBuilder('t')
            ->where('t.estCloture = false OR t.closedAt > :cutoff')
            ->setParameter('cutoff', $cutoff)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}