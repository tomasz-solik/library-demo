<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\GetBook;

use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;

final readonly class GetBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function __invoke(
        GetBookQuery $query
    ): BookResponse {
        $book = $this->bookRepository->findById($query->id);

        if ($book === null) {
            throw new BookNotFoundException();
        }

        return new BookResponse(
            $book->getId(),
            $book->getSerialNumber(),
            $book->getTitle(),
            $book->getAuthor(),
            $book->isBorrowed()
        );
    }
}
