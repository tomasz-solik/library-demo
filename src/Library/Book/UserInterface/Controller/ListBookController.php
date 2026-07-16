<?php

namespace App\Library\Book\UserInterface\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book', name: 'api_books_list', methods: ['GET'])]
final class ListBookController
{
    #[OA\Get(
        summary: 'Get book lists',
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 401, description: 'Unauthorized'),
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
