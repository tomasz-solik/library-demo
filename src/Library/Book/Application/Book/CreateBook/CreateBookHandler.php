<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\CreateBook;

use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Exception\BookSerialNumberAlreadyExistsException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private EntityManagerInterface $entityManager
    ) {
    }


    public function __invoke(
        CreateBookCommand $command
    ): Book {
        if ($this->bookRepository->existsBySerialNumber(
            $command->serialNumber
        )) {
            throw new BookSerialNumberAlreadyExistsException();
        }

        $book = new Book();
        $book->setSerialNumber($command->serialNumber);
        $book->setTitle($command->title);
        $book->setAuthor( $command->author);

        $this->bookRepository->save($book);
        $this->entityManager->flush();

        return $book;
    }
}
