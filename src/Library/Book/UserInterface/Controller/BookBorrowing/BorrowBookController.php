<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\BookBorrowing;

use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookCommand;
use App\Library\Book\Application\BookBorrowing\BorrowBook\BorrowBookHandler;
use App\Library\Book\Domain\Exception\BookAlreadyBorrowedException;
use App\Library\Book\Domain\Exception\BookIsNotBorrowedException;
use App\Library\Book\Domain\Exception\BookNotFoundException;
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
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Book identifier',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['borrowerCardNumber'],
                properties: [
                    new OA\Property(
                        property: 'borrowerCardNumber',
                        description: 'Library card number of borrower',
                        type: 'string',
                        example: '123456'
                    ),
                ]
            )
        ),
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
        try {
            $command = new BorrowBookCommand(
                $id,
                $data['borrowerCardNumber'] ?? ''
            );

            $errors = $validator->validate($command);

            if (count($errors) > 0) {

                return new JsonResponse([
                    'message' => 'Validation failed',
                    'errors' => array_map(
                        fn($error) => [
                            'field' => $error->getPropertyPath(),
                            'message' => $error->getMessage()
                        ],
                        iterator_to_array($errors)
                    )
                ], Response::HTTP_BAD_REQUEST);
            }

            $handler($command);

            return new JsonResponse(
                [
                    'message' => 'Borrowed'
                ],
                Response::HTTP_CREATED
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
        } catch (BookAlreadyBorrowedException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }
}
