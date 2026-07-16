<?php

namespace App\Library\Book\UserInterface\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}', name: 'api_book_celete', methods: ['DELETE'])]
final class DeleteBookController
{
    #[OA\Get(
        summary: 'Delete book',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 400, description: 'Bad data'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(int $id): JsonResponse
    {
        return new JsonResponse([
            'message' => 'book:'.$id
        ]);
    }
}
