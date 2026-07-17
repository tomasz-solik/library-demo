<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\Book;

use App\Library\Book\Application\Book\GetBook\GetBookHandler;
use App\Library\Book\Application\Book\GetBook\GetBookQuery;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}', name: 'api_book_one', methods: ['GET'])]
final class GetBookController
{
    #[OA\Get(
        summary: 'Get one book',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(int $id, GetBookHandler $handler): JsonResponse
    {
        try {
            $book = $handler(
                new GetBookQuery($id)
            );

            return new JsonResponse([
                'id' => $book->id,
                'serialNumber' => $book->serialNumber,
                'title' => $book->title,
                'author' => $book->author,
                'isBorrowed' => $book->isBorrowed,
                'borrowedBy' => [
                    'borrowerCardNumber' => $book->borrowerCardNumber,
                    'borrowedAt' => $book->borrowedAt,
                ]
            ]);
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
