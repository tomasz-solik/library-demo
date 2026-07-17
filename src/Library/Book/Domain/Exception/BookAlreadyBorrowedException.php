<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Exception;

use RuntimeException;

final class BookAlreadyBorrowedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book already borrowed');
    }
}
