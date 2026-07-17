<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\BookBorrowing;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}/borrow', name: 'api_book_borrow', methods: ['UPDATE'])]
final class BorrowBookController
{
    #[OA\Get(
        summary: 'Borrow book',
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'books list'
        ]);
    }
}
