<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\Book;

use App\Library\Book\Application\Book\ListBook\ListBookHandler;
use App\Library\Book\Application\Book\ListBook\ListBookQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book', name: 'api_book_list', methods: ['GET'])]
final class ListBookController
{
    #[OA\Get(
        summary: 'Get book lists',
        responses: [
            new OA\Response(response: 200, description: 'Data'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(ListBookHandler $handler): JsonResponse
    {
        $books = $handler(
            new ListBookQuery()
        );

        return new JsonResponse($books);
    }
}
