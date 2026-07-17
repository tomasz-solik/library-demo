<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\CreateBook;

use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;

final readonly class CreateBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }


    public function __invoke(
        CreateBookCommand $command
    ): Book {
        $book = new Book();
        $book->setSerialNumber($command->serialNumber);
        $book->setTitle($command->title);
        $book->setAuthor( $command->author);

        $this->bookRepository->save($book);

        return $book;
    }
}
