<?php

declare(strict_types=1);

namespace App\Library\Book\Application\BookBorrowing\BorrowBook;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class BorrowBookCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $bookId,
        #[Assert\NotBlank(message: 'Borrower card number cannot be empty')]
        #[Assert\Regex(
            pattern: '/^\d{6}$/',
            message: 'Borrower card number must contain exactly 6 digits'
        )]
        public string $borrowerCardNumber
    ) {
    }
}
