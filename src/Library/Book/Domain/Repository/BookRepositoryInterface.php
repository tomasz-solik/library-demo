<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Repository;

use App\Library\Book\Domain\Entity\Book;

interface BookRepositoryInterface
{
    public function save(Book $book): void;

    /**
     * @param int $id
     * @return Book|null
     */
    public function findById(int $id): ?Book;

    /**
     * @return Book[]
     */
    public function findAll(): array;
}
