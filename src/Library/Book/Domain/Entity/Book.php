<?php

declare(strict_types=1);

namespace App\Library\Book\Domain\Entity;

use App\Library\Book\Domain\Exception\CannotDeleteBorrowedBookException;
use App\Library\Book\Infrastructure\Repository\BookRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'serial_number', length: 6, unique: true)]
    private string $serialNumber;

    #[ORM\Column(name: 'title', length: 255)]
    private string $title;

    #[ORM\Column(name: 'author', length: 255)]
    private string $author;

    #[ORM\Column(name: 'is_borrowed', type: 'boolean', options: ['default' => false])]
    private bool $isBorrowed = false;

    #[ORM\Column(name: 'deleted_at', type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\OneToMany(
        targetEntity: BookBorrowing::class,
        mappedBy: 'book'
    )]
    private Collection $bookBorrowings;

    public function __construct()
    {
        $this->bookBorrowings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isBorrowed(): bool
    {
        return $this->isBorrowed;
    }

    public function setBorrowed(bool $isBorrowed): static
    {
        $this->isBorrowed = $isBorrowed;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function delete(): void
    {
        if ($this->isBorrowed()) {
            throw new CannotDeleteBorrowedBookException();
        }

        $this->deletedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, BookBorrowing>
     */
    public function getBookBorrowings(): Collection
    {
        return $this->bookBorrowings;
    }

    /**
     * @param BookBorrowing $bookBorrowing
     * @return $this
     */
    public function addBookBorrowing(BookBorrowing $bookBorrowing): static
    {
        if (!$this->bookBorrowings->contains($bookBorrowing)) {
            $this->bookBorrowings->add($bookBorrowing);
            $bookBorrowing->setBook($this);
        }

        return $this;
    }
}
