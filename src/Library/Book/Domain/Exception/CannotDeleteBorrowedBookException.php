<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Exception;

use RuntimeException;

final class CannotDeleteBorrowedBookException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'Cannot delete borrowed book'
        );
    }
}
