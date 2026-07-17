<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\ListBook;

use App\Library\Book\Domain\Repository\BookRepositoryInterface;

final readonly class ListBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    /**
     * @return ListBookResponse[]
     */
    public function __invoke(
        ListBookQuery $query
    ): array {
        $books = $this->bookRepository->findAll();

        return array_map(
            fn ($book) => new ListBookResponse(
                $book->getId(),
                $book->getSerialNumber(),
                $book->getTitle(),
                $book->getAuthor(),
                $book->isBorrowed()
            ),
            $books
        );
    }
}
