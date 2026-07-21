<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\BookBorrowing\BorrowBook;

use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookCommand;
use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookHandler;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Entity\BookBorrowing;
use App\Library\Book\Domain\Exception\BookAlreadyBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookBorrowingRepositoryInterface;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class BorrowBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private BookBorrowingRepositoryInterface&MockObject $borrowingRepository;
    private EntityManagerInterface&MockObject $entityManager;
    private BorrowBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->borrowingRepository = $this->createMock(BookBorrowingRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->entityManager
            ->method('wrapInTransaction')
            ->willReturnCallback(fn (callable $func) => $func());

        $this->handler = new BorrowBookHandler(
            $this->bookRepository,
            $this->borrowingRepository,
            $this->entityManager
        );
    }

    public function testBorrowsBookSuccessfully(): void
    {
        $bookId = 1;
        $cardNumber = '123456';
        $command = new BorrowBookCommand(bookId: $bookId, borrowerCardNumber: $cardNumber);

        $book = $this->createMock(Book::class);
        $book->method('isBorrowed')->willReturn(false);
        $book->expects($this->once())->method('borrow');

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($bookId)
            ->willReturn($book);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($book);

        $this->borrowingRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(BookBorrowing::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenBookNotFound(): void
    {
        $nonExistentBookId = 999;
        $command = new BorrowBookCommand(bookId: $nonExistentBookId, borrowerCardNumber: '123456');

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($nonExistentBookId)
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

        $this->expectException(BookNotFoundException::class);

        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenBookIsAlreadyBorrowed(): void
    {
        $bookId = 1;
        $command = new BorrowBookCommand(bookId: $bookId, borrowerCardNumber: '123456');

        $book = $this->createMock(Book::class);
        $book->method('isBorrowed')->willReturn(true);
        $book->expects($this->never())->method('borrow');

        $this->bookRepository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with($bookId)
            ->willReturn($book);

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->borrowingRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookAlreadyBorrowedException::class);

        ($this->handler)($command);
    }
}
