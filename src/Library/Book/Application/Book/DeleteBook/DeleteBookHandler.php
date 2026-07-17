<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\DeleteBook;

use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;

final readonly class DeleteBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function __invoke(
        DeleteBookCommand $command
    ): void {
        $book = $this->bookRepository->findById($command->bookId);

        if ($book === null) {
            throw new BookNotFoundException();
        }

        $book->delete();

        $this->bookRepository->save($book);
    }
}
