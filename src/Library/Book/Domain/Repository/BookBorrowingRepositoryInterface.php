<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Repository;

use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Entity\BookBorrowing;

interface BookBorrowingRepositoryInterface
{
    public function save(BookBorrowing $borrowing): void;

    public function findActiveBorrowingForBook(Book $book): ?BookBorrowing;
}
