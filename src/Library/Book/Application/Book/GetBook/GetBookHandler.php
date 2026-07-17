<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\GetBook;

use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use App\Library\Book\Infrastructure\Repository\BookBorrowingRepository;

final readonly class GetBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookBorrowingRepository $bookBorrowingRepository,
    ) {
    }

    public function __invoke(
        GetBookQuery $query
    ): BookResponse {
        $book = $this->bookRepository->findById($query->id);

        if ($book === null) {
            throw new BookNotFoundException();
        }

        $borrowing = $this->bookBorrowingRepository
            ->findActiveBorrowingForBook($book);

        return new BookResponse(
            $book->getId(),
            $book->getSerialNumber(),
            $book->getTitle(),
            $book->getAuthor(),
            $book->isBorrowed(),
            $borrowing?->getBorrowerCardNumber(),
            $borrowing?->getBorrowedAt()?->format('Y-m-d H:i:s')

        );
    }
}
