<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\BookBorrowing;

use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookCommand;
use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookHandler;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/book/{id}/borrow', name: 'api_book_borrow', methods: ['POST'])]
final class BorrowBookController
{
    #[OA\Post(
        summary: 'Borrow book',
        responses: [
            new OA\Response(response: 204, description: 'Borrowed'),
            new OA\Response(response: 404, description: 'Not Found'),
        ],
    )]
    public function __invoke(
        int $id,
        Request $request,
        BorrowBookHandler $handler,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $command = new BorrowBookCommand(
            $id,
            $data['borrowerCardNumber'] ?? ''
        );

        $errors = $validator->validate($command);

        if (count($errors) > 0) {

            return new JsonResponse(
                [
                    'errors' => (string) $errors
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $handler($command);

        return new JsonResponse(
            [
                'message' => 'Borrowed'
            ],
            Response::HTTP_CREATED
        );
    }
}
