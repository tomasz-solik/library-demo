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

    #[ORM\Column(length: 6)]
    private string $borrower_card_number;

    #[ORM\Column]
    private DateTimeImmutable $borrowed_at;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $returned_at = null;

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
        return $this->borrower_card_number;
    }

    public function setBorrowerCardNumber(string $borrower_card_number): static
    {
        $this->borrower_card_number = $borrower_card_number;

        return $this;
    }

    public function getBorrowedAt(): DateTimeImmutable
    {
        return $this->borrowed_at;
    }

    public function setBorrowedAt(DateTimeImmutable $borrowed_at): static
    {
        $this->borrowed_at = $borrowed_at;

        return $this;
    }

    public function getReturnedAt(): ?DateTimeImmutable
    {
        return $this->returned_at;
    }

    public function setReturnedAt(?DateTimeImmutable $returned_at): static
    {
        $this->returned_at = $returned_at;

        return $this;
    }
}
