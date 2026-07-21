<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\Book\ListBook;

use App\Library\Book\Application\Book\ListBook\ListBookHandler;
use App\Library\Book\Application\Book\ListBook\ListBookQuery;
use App\Library\Book\Application\Book\ListBook\ListBookResponse;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ListBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private ListBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->handler = new ListBookHandler($this->bookRepository);
    }

    public function testReturnsMappedListOfBooks(): void
    {
        $query = new ListBookQuery();

        $book1 = $this->createStub(Book::class);
        $book1->method('getId')->willReturn(1);
        $book1->method('getSerialNumber')->willReturn('ISBN-111');
        $book1->method('getTitle')->willReturn('Czysty Kod');
        $book1->method('getAuthor')->willReturn('Robert C. Martin');
        $book1->method('isBorrowed')->willReturn(false);

        $book2 = $this->createStub(Book::class);
        $book2->method('getId')->willReturn(2);
        $book2->method('getSerialNumber')->willReturn('ISBN-222');
        $book2->method('getTitle')->willReturn('DDD');
        $book2->method('getAuthor')->willReturn('Eric Evans');
        $book2->method('isBorrowed')->willReturn(true);

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$book1, $book2]);

        $result = ($this->handler)($query);

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(ListBookResponse::class, $result);

        $this->assertSame(1, $result[0]->id);
        $this->assertSame('ISBN-111', $result[0]->serialNumber);
        $this->assertSame('Czysty Kod', $result[0]->title);
        $this->assertSame('Robert C. Martin', $result[0]->author);
        $this->assertFalse($result[0]->isBorrowed);

        $this->assertSame(2, $result[1]->id);
        $this->assertSame('ISBN-222', $result[1]->serialNumber);
        $this->assertSame('DDD', $result[1]->title);
        $this->assertSame('Eric Evans', $result[1]->author);
        $this->assertTrue($result[1]->isBorrowed);
    }

    public function testReturnsEmptyArrayWhenNoBooksFound(): void
    {
        $query = new ListBookQuery();

        $this->bookRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = ($this->handler)($query);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
