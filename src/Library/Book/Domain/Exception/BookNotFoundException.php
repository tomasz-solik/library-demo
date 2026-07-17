<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Exception;

use RuntimeException;

final class BookNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book not found');
    }
}
