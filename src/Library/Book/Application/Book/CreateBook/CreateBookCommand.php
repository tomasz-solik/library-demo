<?php

declare(strict_types=1);

namespace App\Library\Book\Application\Book\CreateBook;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateBookCommand
{
    public function __construct(
        #[Assert\NotBlank(
            message: 'Serial number is required'
        )]
        #[Assert\Regex(
            pattern: '/^\d{6}$/',
            message: 'Serial number must contain exactly 6 digits'
        )]
        public string $serialNumber,
        #[Assert\NotBlank(
            message: 'Title is required'
        )]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Title cannot exceed {{ limit }} characters'
        )]
        public string $title,
        #[Assert\NotBlank(
            message: 'Author is required'
        )]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Author cannot exceed {{ limit }} characters'
        )]
        public string $author
    ) {
    }
}
