<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\GetBook;

final readonly class GetBookQuery
{
    public function __construct(
        public int $id
    ) {
    }
}
