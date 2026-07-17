<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\BookBorrowing;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}/return', name: 'api_book_return', methods: ['UPDATE'])]
final class ReturnBookController
{
    #[OA\post(
        summary: 'Return book',
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
