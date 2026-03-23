<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Informations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Informations>
 */
class InformationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Informations::class);
    }

    /**
     * Retourne toutes les informations appartenant à une catégorie donnée.
     *
     * @return Informations[]
     */
    public function findByCategory(Category $category): array
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.categories', 'c')
            ->andWhere('c = :category')
            ->setParameter('category', $category)
            ->orderBy('i.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}