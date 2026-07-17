<?php

declare(strict_types=1);

namespace App\Library\Book\Infrastructure\Repository;

use App\Library\Book\Domain\Entity\BookBorrowing;
use App\Library\Book\Domain\Repository\BookBorrowingRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookBorrowing>
 */
class BookBorrowingRepository extends ServiceEntityRepository implements BookBorrowingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookBorrowing::class);
    }

    //    /**
    //     * @return BookBorrowing[] Returns an array of BookBorrowing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BookBorrowing
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function save(BookBorrowing $borrowing): void
    {
        $this->getEntityManager()->persist($borrowing);
    }
}
