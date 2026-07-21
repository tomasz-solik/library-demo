<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\Book\DeleteBook;

use App\Library\Book\Application\Book\DeleteBook\DeleteBookCommand;
use App\Library\Book\Application\Book\DeleteBook\DeleteBookHandler;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DeleteBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private EntityManagerInterface&MockObject $entityManager;
    private DeleteBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->handler = new DeleteBookHandler(
            $this->bookRepository,
            $this->entityManager
        );
    }

    public function testDeletesBookSuccessfully(): void
    {
        $bookId = 1;
        $command = new DeleteBookCommand(bookId: $bookId);

        $book = $this->createMock(Book::class);
        $book->expects($this->once())
            ->method('delete');

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($book);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenBookNotFound(): void
    {
        $nonExistentBookId = 999;
        $command = new DeleteBookCommand(bookId: $nonExistentBookId);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($nonExistentBookId)
            ->willReturn(null);

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookNotFoundException::class);

        ($this->handler)($command);
    }
}
