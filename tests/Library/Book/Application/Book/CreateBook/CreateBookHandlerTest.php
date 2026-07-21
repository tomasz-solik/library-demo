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
            serialNumber: 'ISBN-12345',
            title: 'Czysty Kod',
            author: 'Robert C. Martin'
        );

        $this->bookRepository
            ->expects($this->once())
            ->method('existsBySerialNumber')
            ->with('ISBN-12345')
            ->willReturn(false);

        $this->bookRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Book::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $book = ($this->handler)($command);

        // 3. Assert (Asercje)
        $this->assertInstanceOf(Book::class, $book);
        $this->assertSame('ISBN-12345', $book->getSerialNumber());
        $this->assertSame('Czysty Kod', $book->getTitle());
        $this->assertSame('Robert C. Martin', $book->getAuthor());
    }

    public function testThrowsExceptionWhenSerialNumberAlreadyExists(): void
    {
        // 1. Arrange
        $command = new CreateBookCommand(
            serialNumber: 'EXISTING-SERIAL',
            title: 'Dowolny tytuł',
            author: 'Dowolny autor'
        );

        // Repozytorium zgłasza, że numer już istnieje
        $this->bookRepository
            ->expects($this->once())
            ->method('existsBySerialNumber')
            ->with('EXISTING-SERIAL')
            ->willReturn(true);

        $this->bookRepository
            ->expects($this->never())
            ->method('save');

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->expectException(BookSerialNumberAlreadyExistsException::class);

        // 2. Act
        ($this->handler)($command);
    }
}
