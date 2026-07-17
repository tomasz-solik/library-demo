<?php

declare(strict_types=1);

namespace App\Library\Book\Infrastructure\Repository;

use App\Library\Book\Domain\Entity\Book;
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

    public function save(BookBorrowing $borrowing): void
    {
        $this->getEntityManager()->persist($borrowing);
    }

    public function findActiveBorrowingForBook(Book $book): ?BookBorrowing
    {
        return $this->createQueryBuilder('bb')
            ->where('bb.book = :book')
            ->andWhere('bb.returnedAt IS NULL')
            ->setParameter('book', $book)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
