<?php

namespace App\Library\Book\Domain\Exception;

use RuntimeException;

final class BookIsNotBorrowedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Book is not borrowed'
        );
    }
}
