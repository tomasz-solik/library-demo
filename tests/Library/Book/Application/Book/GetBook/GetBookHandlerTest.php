<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\Book\GetBook;

use App\Library\Book\Application\Book\GetBook\BookResponse;
use App\Library\Book\Application\Book\GetBook\GetBookHandler;
use App\Library\Book\Application\Book\GetBook\GetBookQuery;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Entity\BookBorrowing;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use App\Library\Book\Infrastructure\Repository\BookBorrowingRepository;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GetBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private BookBorrowingRepository&MockObject $bookBorrowingRepository;
    private GetBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);

        $this->bookBorrowingRepository = $this->getMockBuilder(BookBorrowingRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findActiveBorrowingForBook'])
            ->getMock();

        $this->handler = new GetBookHandler(
            $this->bookRepository,
            $this->bookBorrowingRepository
        );
    }

    public function testReturnsBookResponseWithActiveBorrowing(): void
    {
        $bookId = 1;
        $query = new GetBookQuery(id: $bookId);

        $book = $this->createStub(Book::class);
        $book->method('getId')->willReturn($bookId);
        $book->method('getSerialNumber')->willReturn('123456');
        $book->method('getTitle')->willReturn('Czysty Kod');
        $book->method('getAuthor')->willReturn('Robert C. Martin');
        $book->method('isBorrowed')->willReturn(true);

        $borrowedAt = new DateTimeImmutable('2026-01-15 10:00:00');
        $borrowing = $this->createStub(BookBorrowing::class);
        $borrowing->method('getBorrowerCardNumber')->willReturn('123456');
        $borrowing->method('getBorrowedAt')->willReturn($borrowedAt);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->bookBorrowingRepository
            ->expects($this->once())
            ->method('findActiveBorrowingForBook')
            ->with($book)
            ->willReturn($borrowing);

        $response = ($this->handler)($query);

        $this->assertInstanceOf(BookResponse::class, $response);
        $this->assertSame($bookId, $response->id);
        $this->assertSame('123456', $response->serialNumber);
        $this->assertSame('Czysty Kod', $response->title);
        $this->assertSame('Robert C. Martin', $response->author);
        $this->assertTrue($response->isBorrowed);
        $this->assertSame('123456', $response->borrowerCardNumber);
        $this->assertSame('2026-01-15 10:00:00', $response->borrowedAt);
    }

    public function testReturnsBookResponseWithoutBorrowing(): void
    {
        $bookId = 2;
        $query = new GetBookQuery(id: $bookId);

        $book = $this->createStub(Book::class);
        $book->method('getId')->willReturn($bookId);
        $book->method('getSerialNumber')->willReturn('123456');
        $book->method('getTitle')->willReturn('DDD');
        $book->method('getAuthor')->willReturn('Eric Evans');
        $book->method('isBorrowed')->willReturn(false);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $this->bookBorrowingRepository
            ->expects($this->once())
            ->method('findActiveBorrowingForBook')
            ->with($book)
            ->willReturn(null);

        $response = ($this->handler)($query);

        $this->assertInstanceOf(BookResponse::class, $response);
        $this->assertSame($bookId, $response->id);
        $this->assertFalse($response->isBorrowed);
        $this->assertNull($response->borrowerCardNumber);
        $this->assertNull($response->borrowedAt);
    }

    public function testThrowsExceptionWhenBookNotFound(): void
    {
        $nonExistentBookId = 999;
        $query = new GetBookQuery(id: $nonExistentBookId);

        $this->bookRepository
            ->expects($this->once())
            ->method('findById')
            ->with($nonExistentBookId)
            ->willReturn(null);

        $this->bookBorrowingRepository
            ->expects($this->never())
            ->method('findActiveBorrowingForBook');

        $this->expectException(BookNotFoundException::class);

        ($this->handler)($query);
    }
}
