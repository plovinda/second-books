<?php


namespace App\Service;


use App\Entity\Book;
use App\Form\CreateBookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypeGuesserInterface;
use Symfony\Contracts\Cache\CacheInterface;

class BookService
{
    private $cache;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FormBuilderInterface
     */
    private $formBuilder;
    /**
     * @var FileBag
     */
    private $fileBag;
    /**
     * @var MimeTypeGuesserInterface
     */
    private $mimeTypeGuesser;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     *
     * @param CacheInterface $cache
     * @param FormFactoryInterface $formFactory
     * @param MimeTypeGuesserInterface $mimeTypeGuesser
     * @param EntityManager $entityManager
     */

    public function __construct(CacheInterface $cache,FormFactoryInterface $formFactory,
                                MimeTypeGuesserInterface $mimeTypeGuesser,EntityManagerInterface $entityManager)
    {
       $this->cache = $cache;
       $this->formFactory = $formFactory;
       $this->mimeTypeGuesser = $mimeTypeGuesser;
       $this->entityManager = $entityManager;

    }

    public function listBooks($bookRepository)
    {
        $books = $bookRepository->findAll();
        return $books;
    }

   /* public function getBookByCheckingCache($bookRepository,$id)
    {
        $this->cache->get('book_'.md5($id),function () use($bookRepository,$id) {
            return $bookRepository->find($id);
        });
    }*/
}