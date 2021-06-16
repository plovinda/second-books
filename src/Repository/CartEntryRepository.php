<?php

namespace App\Repository;

use App\Entity\CartEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 * @method CartEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartEntry[]    findAll()
 * @method CartEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartEntry::class);
    }

    // /**
    //  * @return CartEntry[] Returns an array of CartEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /*
    public function findOneBySomeField($value): ?CartEntry
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findCartEntryByBookId($bookId)
    {
         return $this->createQueryBuilder('c')
            ->andWhere('c.book_id = :val')
            ->setParameter('val', $bookId)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCartEntriesByUser($userId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.userId = :val')
            ->setParameter('val', $userId)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
