<?php

declare(strict_types=1);

namespace App\Tests\Library\Book\Application\Book\CreateBook;

use App\Library\Book\Application\Book\CreateBook\CreateBookCommand;
use App\Library\Book\Application\Book\CreateBook\CreateBookHandler;
use App\Library\Book\Domain\Entity\Book;
use App\Library\Book\Domain\Exception\BookSerialNumberAlreadyExistsException;
use App\Library\Book\Domain\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateBookHandlerTest extends TestCase
{
    private BookRepositoryInterface&MockObject $bookRepository;
    private EntityManagerInterface&MockObject $entityManager;
    private CreateBookHandler $handler;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->handler = new CreateBookHandler(
            $this->bookRepository,
            $this->entityManager
        );
    }

    public function testCreatesBookSuccessfully(): void
    {
        $command = new CreateBookCommand(
            serialNumber: '123456',
            title: 'Czysty Kod',
            author: 'Robert C. Martin'
        );

        $this->bookRepository
            ->expects($this->once())
            ->method('existsBySerialNumber')
            ->with('123456')
            ->willReturn(false);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Book::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $book = ($this->handler)($command);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertSame('123456', $book->getSerialNumber());
        $this->assertSame('Czysty Kod', $book->getTitle());
        $this->assertSame('Robert C. Martin', $book->getAuthor());
    }

    public function testThrowsExceptionWhenSerialNumberAlreadyExists(): void
    {
        $command = new CreateBookCommand(
            serialNumber: '123456',
            title: 'Dowolny tytuł',
            author: 'Dowolny autor'
        );

        $this->bookRepository
            ->expects($this->once())
            ->method('existsBySerialNumber')
            ->with('123456')
            ->willReturn(true);

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookSerialNumberAlreadyExistsException::class);

        ($this->handler)($command);
    }
}
