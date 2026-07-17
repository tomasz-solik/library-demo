<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Entity;

use App\Library\Book\Infrastructure\Repository\BookBorrowingRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookBorrowingRepository::class)]
class BookBorrowing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'bookBorrowings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\Column(name: 'borrower_card_number', length: 6)]
    private string $borrowerCardNumber;

    #[ORM\Column(name: 'borrowed_at', type: 'datetime_immutable')]
    private DateTimeImmutable $borrowedAt;

    #[ORM\Column(name: 'returned_at', type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $returnedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }


    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getBorrowerCardNumber(): string
    {
        return $this->borrowerCardNumber;
    }

    public function setBorrowerCardNumber(string $borrowerCardNumber): static
    {
        $this->borrowerCardNumber = $borrowerCardNumber;

        return $this;
    }

    public function getBorrowedAt(): DateTimeImmutable
    {
        return $this->borrowedAt;
    }

    public function setBorrowedAt(DateTimeImmutable $borrowedAt): static
    {
        $this->borrowedAt = $borrowedAt;

        return $this;
    }

    public function getReturnedAt(): ?DateTimeImmutable
    {
        return $this->returnedAt;
    }

    public function returnBook(): static
    {
        $this->returnedAt = new DateTimeImmutable();

        return $this;
    }

    public static function create(
        Book $book,
        string $borrowerCardNumber
    ): self {

        $borrowing = new self();

        $borrowing->book = $book;
        $borrowing->borrowerCardNumber = $borrowerCardNumber;
        $borrowing->borrowedAt = new \DateTimeImmutable();

        return $borrowing;
    }
}
