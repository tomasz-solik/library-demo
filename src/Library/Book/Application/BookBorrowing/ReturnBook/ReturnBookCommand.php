<?php

declare(strict_types=1);

namespace App\Library\Book\Application\BookBorrowing\ReturnBook;

final readonly class ReturnBookCommand
{
    public function __construct(
        public int $bookId
    ) {
    }
}
