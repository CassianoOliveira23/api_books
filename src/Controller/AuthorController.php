<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'new_author', methods: ['POST'])]
    public function new(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $author = new Author();
        $author->setName($parameters['name']);
        $em->persist($author);
        $em->flush();
        return $this->json(["Author saved successfully"]);
    }

    #[Route('/', name: 'author_get_all', methods:['GET'])]
    public function getAll(AuthorRepository $authorRepository): JsonResponse
    {
        $authors = $authorRepository->findAll();
        return $this->json($authors);
    }

   
}
