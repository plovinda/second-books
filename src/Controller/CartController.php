<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartEntry;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\CartEntryRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/add-to-cart/{bookId}",name="add.to.cart")
     * @param $bookId
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param CartRepository $cartRepository
     * @return RedirectResponse
     */
    public function addToCart($bookId,Request $request,BookRepository $bookRepository,CartRepository $cartRepository)
    {
        $criteria = array('user'=>$this->getUser());
        /*
         * Check if there is no existing cart entry, the user is adding to cart for the first time.
         * */
        $existingCart = $cartRepository->findOneBy($criteria);
        $book = $bookRepository->find($bookId);
        $quantity = $request->get('quantity');
        if(is_null($existingCart)) //User is a new user and no cart added so far
        {
            $em = $this->getDoctrine()->getManager();
            $cart = new Cart();
            $cart->setUser($this->getUser());
            $em->persist($cart);
            $em->flush();

            $cartEntry = new CartEntry();
            $cartEntry->setQuantity($quantity);
            $cartEntry->setBasePrice($book->getPrice());
            $cartEntry->setTotalPrice($book->getPrice()*$quantity);
            $cartEntry->addBookId($book);
            dump($this->calculateTotalPrice($cart));
            $cart->addCartEntry($cartEntry);
            $cart->setTotalPrice($this->calculateTotalPrice($cart));

            $em->persist($cartEntry);
            $em->flush();
        }
        else
        {   //The user has an existing cart and is adding another product
            $this->updateCart($existingCart,$book,$quantity);
        }
        return $this->redirectToRoute('index.book');
    }

    /**
     * @param $existingCart
     * @param $book
     * @param $quantity
     */
    public function updateCart($existingCart,$book,$quantity)
    {
        //Get the existing cart and retrieve the cart entries
        $existingCartEntries = $existingCart->getCartEntry();
        $em = $this->getDoctrine()->getManager();
        $existingCartEntryFound = $this->checkIfCartEntryExists($existingCartEntries,$book);

            /*
             * If the book Id in the cart entry does not match the book Id user selected,
             * then another book is being added to cart
             * */
            if(is_null($existingCartEntryFound))
            {
                $cartEntry = new CartEntry();
                $cartEntry->addBookId($book);
                $cartEntry->setBasePrice($book->getPrice());
                $cartEntry->setTotalPrice($book->getPrice()*$quantity);
                $cartEntry->setQuantity($quantity);
                $existingCart->addCartEntry($cartEntry);
                $existingCart->setTotalPrice($this->calculateTotalPrice($existingCart));
               // $existingCart->setTotalPrice($cartEntry->getTotalPrice());
                $em->persist($cartEntry);
                $em->flush();
            }
            /*
             * If the book Id in the cart entry matches the book Id user selected, it means quantity is being changed
             * */
            else {
                $existingCartEntryFound->setQuantity($existingCartEntryFound->getQuantity() + $quantity);
                $existingCartEntryFound->setTotalPrice($existingCartEntryFound->getBasePrice()
                                            * $existingCartEntryFound->getQuantity());
                $existingCart->setTotalPrice($this->calculateTotalPrice($existingCart));
                $em->flush();
            }
    }

    /**
     * @Route("/view-cart",name="view.cart")
     * @param CartRepository $cartRepository
     * @return Response
     */
    public function viewCart(CartRepository $cartRepository)
    {
        $currentUser = $this->getUser();
        $criteria = array('user'=>$currentUser);
        $cart = $cartRepository->findOneBy(($criteria));
        return $this->render('cart/view_cart.html.twig',[
            'cart' => $cart
        ]);
    }

    /**
     * @param Cart $cart
     * @return string|null
     */
    public function calculateTotalPrice(Cart $cart)
    {
        $cartEntries = $cart->getCartEntry();
        $totalPriceOfCart = null;
        foreach ($cartEntries as $entry) {
            $totalPriceOfCart = $totalPriceOfCart + $entry->getTotalPrice();
        }
        return $totalPriceOfCart;
    }

    /**
     * @param $existingCartEntries
     * @param $book
     * @return |null
     */
    public function checkIfCartEntryExists($existingCartEntries,$book)
    {
        $existingCartEntryFound = null;
        foreach ($existingCartEntries as $existingCartEntry) {
            //Check if the quantity is being changed or a new product is being added
            $existingBook = $existingCartEntry->getBookId()[0]; //$existingCartEntry->getValues();
            if(isset($existingBook)){
                if(($existingBook->getId())==$book->getId())
                {
                    $existingCartEntryFound = $existingCartEntry;

                }
            }

        }
        return $existingCartEntryFound;
    }

    /**
     * @Route("/remove-from-cart/{cartEntryId}",name="remove.cart")
     * @param $cartEntryId
     * @param CartRepository $cartRepository
     * @param CartEntryRepository $cartEntryRepository
     * @return RedirectResponse
     */
    public function removeFromCart($cartEntryId,CartRepository $cartRepository,CartEntryRepository $cartEntryRepository)
    {
        $currentUser = $this->getUser();
        $criteria = array('user'=>$currentUser);
        $cart = $cartRepository->findOneBy(($criteria));
        $cartEntry = $cartEntryRepository->find($cartEntryId);
        $cart->removeCartEntry($cartEntry);
        $cart->setTotalPrice($this->calculateTotalPrice($cart));
        $em = $this->getDoctrine()->getManager();
        $em->remove($cartEntry);
        $em->flush();
        return $this->redirect($this->generateUrl("view.cart"));

    }
}
