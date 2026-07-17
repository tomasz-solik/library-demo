<?php

declare(strict_types=1);

namespace App\Library\Book\UserInterface\Controller\Book;

use App\Library\Book\Application\Book\CreateBook\CreateBookCommand;
use App\Library\Book\Application\Book\CreateBook\CreateBookHandler;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/book', name: 'api_book_create', methods: ['POST'])]
final class CreateBookController
{
    public function __invoke(
        Request $request,
        CreateBookHandler $handler,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $command = new CreateBookCommand(
            $data['serialNumber'] ?? '',
            $data['title'] ?? '',
            $data['author'] ?? ''
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


        $book = $handler($command);

        return new JsonResponse([
            'id' => $book->getId(),
            'serialNumber' => $book->getSerialNumber(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isBorrowed' => $book->isBorrowed(),
        ], Response::HTTP_CREATED);
    }
}
