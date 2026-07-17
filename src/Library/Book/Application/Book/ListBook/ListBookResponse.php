<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\ListBook;

final readonly class ListBookResponse
{
    public function __construct(
        public int $id,
        public string $serialNumber,
        public string $title,
        public string $author,
        public bool $isBorrowed
    ) {
    }
}

