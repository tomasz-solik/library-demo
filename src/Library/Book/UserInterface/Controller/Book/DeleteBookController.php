<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\Book;

use App\Library\Book\Application\Book\DeleteBook\DeleteBookCommand;
use App\Library\Book\Application\Book\DeleteBook\DeleteBookHandler;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}', name: 'api_book_delete', methods: ['DELETE'])]
final class DeleteBookController
{
    #[OA\Get(
        summary: 'Delete one book',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
            new OA\Response(response: 400, description: 'Bad data'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(int $id, DeleteBookHandler $handler): JsonResponse
    {
        try {
            $handler(
                new DeleteBookCommand($id)
            );

            return new JsonResponse(
                null,
                Response::HTTP_NO_CONTENT
            );

        } catch (BookNotFoundException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
