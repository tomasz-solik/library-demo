<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\DeleteBook;

final readonly class DeleteBookCommand
{
    public function __construct(
        public int $bookId
    ) {
    }
}
