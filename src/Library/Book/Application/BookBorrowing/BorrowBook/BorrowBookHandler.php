<?php

declare(strict_types=1);

namespace App\Library\Book\Application\BookBorrowing\BorrowBook;

use App\Library\Book\Domain\Entity\BookBorrowing;
use App\Library\Book\Domain\Exception\BookAlreadyBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookBorrowingRepositoryInterface;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class BorrowBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookBorrowingRepositoryInterface $borrowingRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(
        BorrowBookCommand $command
    ): void {

        $this->entityManager->wrapInTransaction(
            function () use ($command) {
                $book = $this->bookRepository->findByIdForUpdate($command->bookId);

                if ($book === null) {
                    throw new BookNotFoundException();
                }

                if ($book->isBorrowed()) {
                    throw new BookAlreadyBorrowedException();
                }

                $book->borrow();

                $borrowing = BookBorrowing::create(
                    $book,
                    $command->borrowerCardNumber
                );

                $this->bookRepository->save($book);
                $this->borrowingRepository->save($borrowing);
                $this->entityManager->flush();
            }
        );
    }
}
