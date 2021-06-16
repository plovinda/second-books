<?php

namespace App\Controller;


use App\Repository\BookRepository;
use App\Service\BookService;
use App\Service\MakeCache;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api", name="api_")
 * */
class BookControllerRest extends AbstractFOSRestController
{


    /** @Rest\Get("/restbooks")
     * @param BookRepository $bookRepository
     * @param BookService $bookService
     * @return Response
     */

        public function getBooks(BookRepository $bookRepository, BookService $bookService)
        {
            $books = $bookService->listBooks($bookRepository);
            return $this->handleView($this->view($books));
        }



}
