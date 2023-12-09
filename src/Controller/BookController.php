<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'new_book', methods: ['POST'])]
    public function new(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $author =  $em->getRepository(Author::class)->find($parameters['author']);
        $book = new Book();
        $book->setTitle($parameters['title']);
        $book->setDescription($parameters['description']);
        $book->setNumberPages($parameters['numberPages']);
        $book->setAuthor($author);
        $em->persist($book);
        $em->flush();
        return $this->json(["Book saved successfully"]);
    }

    #[Route('/', name: 'book_get_all', methods:['GET'])]
    public function getAll(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();
        return $this->json($books);
    }

    #[Route('/{id}', name: 'edit_book', methods:["PUT"])]
    public function edit(EntityManagerInterface $em, int $id, Request $request): JsonResponse
    {
        $bookRepository = $em->getRepository(Book::class);
        $book = $bookRepository->find($id);
        $parameters = json_decode($request->getContent(), true);
        $author =  $em->getRepository(Author::class)->find($parameters['author']);

        $book->setTitle($parameters['title']);
        $book->setDescription($parameters['description']);
        $book->setNumberPages($parameters['numberPages']);
        $book->setAuthor($author);
        
        $em->persist($book);
        $em->flush();
        return $this->json(["Congratulations! Book edited successfully"]);
    }

    #[Route('/{id}', name: 'delete_book', methods:["DELETE"])]
    public function delete(EntityManagerInterface $em, int $id): JsonResponse
    {
        $bookRepository = $em->getRepository(Book::class);
        $book = $bookRepository->find($id);
        if(is_null($book)) 
        {
            return $this->json("This book is already deleted");
        }
        $em->remove($book);
        $em->flush();
        return $this->json(["Congratulations! Book deleted successfully"]);
    }

    
}
