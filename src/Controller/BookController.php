<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AddBookFormType;
use App\Form\EditBookFormType;
use App\Service\BookService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/addBook', name: 'app_book_add')]
    public function addBook(Request $request, BookService $BookService): Response
    {
        $book=new Book();
        $form=$this->CreateForm(AddBookFormType::class,$book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $response=$BookService->addBook($form);
            return $this->redirectToRoute('app_admin');

        }

        return $this->render('book/addBookForm.html.twig', [
            'form'=>$form->CreateView(),
        ]);
    }


#[Route('/showAllBooks', name: 'app_book_all_show')]
   public function showAllBooks(Request $request, BookService $BookService):Jsonresponse
   {
        $allBooks=$BookService->showAllBooks();
        return new JsonResponse($allBooks);

   }

    #[Route('/showAvailableBooks', name: 'app_book_available_show')]
    public function showAvailableBooks(Request $request, BookService $BookService):Jsonresponse
    {
        $allBooks=$BookService->showAvailableBooks();
        return new JsonResponse($allBooks);

    }

    #[Route('/showUnAvailableBooks', name: 'app_book_ordered_show')]
    public function showUnAvailableBooks(Request $request, BookService $BookService):Jsonresponse
    {
        $orderedBooks=$BookService->showOrderedBooks();
        return new JsonResponse($orderedBooks);

    }


    #[Route('/EditBook/{id}', name:'app_book_edit_show')]
    public function editBooks(Request $request, BookService $bookService, $id):Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        $book=new Book();
        $bookService->setValuesById($book,$id);
        $form=$this->createForm(EditBookFormType::class,$book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $bookService->UpdateBook($form,$id);
           return  $this->redirectToRoute('app_admin');
        }

        return $this->render('book/addBookForm.html.twig',
        [
            'form'=>$form->createView()
        ]);
    }


    #[Route('/DeleteBook/{id}', name:'app_book_delete_show')]
    public function deleteBooks(Request $request, BookService $bookService, $id):JsonResponse
    {
        if(!$this->isGranted("ROLE_ADMIN"))
         {
             return throw $this->createAccessDeniedException("You have no access to admin Page");
         }

         $book=new Book();
        $quantity=$bookService->BookQuantity($id);

        if($quantity>=1)
        {
            $bookService->DeleteBook($id,$quantity);
        }

        $remainingBooks = $bookService->showAvailableBooks();

        return new JsonResponse($remainingBooks);

     }

    //ordered books history specific to id
    #[Route('/OrderBook/{bookId}', name:'app_order_book')]
    public function orderBook(Request $request, BookService $bookService, OrderService $orderService,$bookId):JsonResponse
    {

        if(!$this->isGranted("ROLE_USER"))
        {
            return throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        $quantity=$bookService->BookQuantity($bookId);

        if($quantity>=1)
        {
            //as order book and delete book both have same logic
            $bookService->DeleteBook($bookId,$quantity);
            $orderService->addOrder($bookId);
        }

        $RemainingBooks=$bookService->showAvailableBooks();

        return new JsonResponse($RemainingBooks);
    }


    #[Route('/OrderedBookHistory', name:'app_order_books_history')]
    public function orderedBookHistory(Request $request, OrderService $orderService):JsonResponse
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            return throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        $OrderedBooks=$orderService->showCustomerOrderHistory();

        return new JsonResponse($OrderedBooks);
    }

    #[Route('/ViewAllCustomers', name:'app_view_all_customers')]
    public function viewAllCustomers(Request $request, OrderService $orderService ):JsonResponse
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        //fetches data of all users who have ordered something
        $allCustomers=$orderService->showAllCustomers();

        return new JsonResponse($allCustomers);
    }

    #[Route('/SoldBooks', name:'app_sold_books')]
    public function soldBooks(Request $request, OrderService $orderService):JsonResponse
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return throw $this->createAccessDeniedException("You have no access to admin Page");
        }

        //fetches data of all users who have ordered something
        $allCustomers=$orderService->showAllSoldBooks();

        return new JsonResponse($allCustomers);
    }



}
