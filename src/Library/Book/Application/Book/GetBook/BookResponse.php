<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\GetBook;

final readonly class BookResponse
{
    public function __construct(
        public int $id,
        public string $serialNumber,
        public string $title,
        public string $author,
        public bool $isBorrowed,
        public ?string $borrowerCardNumber,
        public ?string $borrowedAt,
    ) {
    }
}

