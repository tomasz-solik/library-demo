<?php

declare(strict_types=1);

namespace App\Library\Book\Application\BookBorrowing\ReturnBook;

use App\Library\Book\Domain\Exception\BookIsNotBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookBorrowingRepositoryInterface;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ReturnBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookBorrowingRepositoryInterface $borrowingRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(ReturnBookCommand $command): void
    {
        $this->entityManager->wrapInTransaction(
            function () use ($command) {
                $book = $this->bookRepository->findByIdForUpdate($command->bookId);

                if ($book === null) {
                    throw new BookNotFoundException();
                }

                $borrowing = $this->borrowingRepository
                    ->findActiveBorrowingForBook($book);

                if ($borrowing === null) {
                    throw new BookIsNotBorrowedException();
                }

                $borrowing->returnBook();
                $book->markAsReturned();

                $this->bookRepository->save($book);
                $this->borrowingRepository->save($borrowing);
                $this->entityManager->flush();
            }
        );
    }
}
