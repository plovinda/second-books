<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\CartEntry;
use App\Form\CreateBookType;
use App\Repository\BookRepository;
use App\Repository\CartEntryRepository;
use App\Service\BookService;
use App\Service\MakeCache;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use function PHPUnit\Framework\throwException;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="index.book")
     * @param BookRepository $bookRepository
     * @param BookService $bookService
     * @return Response
     */
    public function index(BookRepository $bookRepository, BookService $bookService): Response
    {
        $books = $bookService->listBooks($bookRepository);
        return $this->render('/book/book_list.html.twig',[
            'books' => $books
        ]);
    }

    /**
     * @Route("/book/show/{id}",name="view.book")
     * @param Book $book
     * @param BookService $bookService
     * @return Response


    public function viewBook(Book $book,BookService $bookService)//Param converter works only work sensio extraframework bundle is installed
    {
        $bookToReturn = $bookService->getBookByCheckingCache($book);
        return $this->render('/book/book_view.html.twig',[
            'book' => $bookToReturn
        ]);
    }
     */

    /** @Route("/book/show/{id}",name="view.book")
     * @param Book $book
     * @return Response
     */

        public function viewBook(Book $book)
        {
            return $this->render('/book/book_view.html.twig',[
                'book' => $book
            ]);
        }

    /**
     * @Route("/book/create", name="create.book")
     * @param Request $request
     * @return Response
     */
    public function createBook(Request $request)
    {
        $book = new Book();
        return $this->formCreate($request,$book, 'book_create');

    }

    /**
     * @Route("/book/remove/{id}",name="remove.book")
     * @param Book $book
     * @param BookRepository $bookRepository
     * @return RedirectResponse
     */
    public function removeBook(Book $book,BookRepository $bookRepository):RedirectResponse
    {

        $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('index.book');
    }

    /**
     * @Route("/book/update/{id}",name="update.book")
     * @param Request $request
     * @param $book
     * @return Response
     */
    public function updateBook(Book $book,Request $request)
    {
        return $this->formCreate($request,$book,'book_update');
    }

    /**
     * @param Request $request
     * @param Book $book
     * @param $templateName
     * @return RedirectResponse|Response
     */
    public function formCreate($request,$book, $templateName)
    {

        $form = $this->createForm(CreateBookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $bookFromForm = $form->getData();
            $file = $request->files->get('create_book')['attachment'];
            if($file) {
                $fileName = md5(uniqid() . '.' . $file->guessClientExtension());
                $file->move(
                    $this->getParameter('uploads_dir'),
                    $fileName
                );
                $bookFromForm->setBookImage($fileName);

            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($bookFromForm);
            $em->flush();
            return $this->redirectToRoute('index.book');
        }
        return $this->render('book/'.'book_create'.'.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
