<?php

namespace App\Library\Book\UserInterface\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book', name: 'api_book_create', methods: ['POST'])]
final class CreateBookController
{
    #[OA\Get(
        summary: 'Create book',
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 400, description: 'Bad data'),
        ],
    )]
    public function __invoke($command): JsonResponse
    {
        return new JsonResponse([
            'message' => 'book:'
        ]);
    }
}
