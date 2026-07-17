<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\BookBorrowing;

use App\Library\Book\Application\BookBorrowing\ReturnBook\ReturnBookCommand;
use App\Library\Book\Application\BookBorrowing\ReturnBook\ReturnBookHandler;
use App\Library\Book\Domain\Exception\BookIsNotBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book/{id}/return', name: 'api_book_return', methods: ['POST'])]
final class ReturnBookController
{
    #[OA\post(
        summary: 'Return book',
        responses: [
            new OA\Response(response: 200, description: 'Book returned'),
            new OA\Response(response: 404, description: 'Not Found'),
            new OA\Response(response: 404, description: 'Conflict'),
        ],
    )]
    public function __invoke(int $id, ReturnBookHandler $handler): JsonResponse
    {
        try {

            $handler(new ReturnBookCommand($id));

            return new JsonResponse(
                [
                    'message' => 'Book returned'
                ],
                Response::HTTP_OK
            );

        } catch (BookNotFoundException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (BookIsNotBorrowedException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }
}
