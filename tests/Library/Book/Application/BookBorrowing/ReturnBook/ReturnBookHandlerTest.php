<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\BookBorrowing\ReturnBook;

use App\Library\Book\Application\BookBorrowing\ReturnBook\ReturnBookCommand;
use App\Library\Book\Application\BookBorrowing\ReturnBook\ReturnBookHandler;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Entity\BookBorrowing;
use App\Library\Book\Domain\Exception\BookIsNotBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookBorrowingRepositoryInterface;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ReturnBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private BookBorrowingRepositoryInterface&MockObject $borrowingRepository;
    private EntityManagerInterface&MockObject $entityManager;
    private ReturnBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->borrowingRepository = $this->createMock(BookBorrowingRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->entityManager
            ->method('wrapInTransaction')
            ->willReturnCallback(fn (callable $func) => $func());

        $this->handler = new ReturnBookHandler(
            $this->bookRepository,
            $this->borrowingRepository,
            $this->entityManager
        );
    }

    public function testReturnsBookSuccessfully(): void
    {
        $bookId = 1;
        $command = new ReturnBookCommand(bookId: $bookId);

        $book = $this->createMock(Book::class);
        $book->expects($this->once())->method('markAsReturned');

        $borrowing = $this->createMock(BookBorrowing::class);
        $borrowing->expects($this->once())->method('returnBook');

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($bookId)
            ->willReturn($book);

        $this->borrowingRepository
            ->expects($this->once())
            ->method('findActiveBorrowingForBook')
            ->with($book)
            ->willReturn($borrowing);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($book);

        $this->borrowingRepository
            ->expects($this->once())
            ->method('save')
            ->with($borrowing);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenBookNotFound(): void
    {
        $nonExistentBookId = 999;
        $command = new ReturnBookCommand(bookId: $nonExistentBookId);

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($nonExistentBookId)
            ->willReturn(null);

        $this->borrowingRepository
            ->expects($this->never())
            ->method('findActiveBorrowingForBook');

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->borrowingRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookNotFoundException::class);

        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenBookIsNotBorrowed(): void
    {
        $bookId = 1;
        $command = new ReturnBookCommand(bookId: $bookId);

        $book = $this->createStub(Book::class);

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($bookId)
            ->willReturn($book);

        $this->borrowingRepository
            ->expects($this->once())
            ->method('findActiveBorrowingForBook')
            ->with($book)
            ->willReturn(null);

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->borrowingRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookIsNotBorrowedException::class);

        ($this->handler)($command);

    }
}
