<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class BookService
{
    public BookRepository $bookRepository;
    private ValidatorInterface $validator;

  public function __construct(BookRepository $bookRepository, ValidatorInterface $validator)
  {
      $this->bookRepository=$bookRepository;
      $this->validator=$validator;
  }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function addBook($form)
  {
      $book=new Book();
      //parsing from form:
      $this->ParseFormAndSetToEntity($form, $book);

      $errors=$this->validator->validate($book);

      if(count($errors)>0)
      {
          return new Response((string)$errors, 400);
      }
      else{

          $this->bookRepository->persist($book, true);
      }

  }

  public function showAllBooks():array
  {
//      $books=$this->entityManager->getRepository(Book::class)->findAll();
      $books=$this->bookRepository->findAll();
      $booksArray=array();

      foreach($books as $book)
      {
          $booksArray[]=array(
              'id: '=>$book->getId(),
              'bookName: '=>$book->getbookName(),
              'Author: '=>$book->getAuthor(),
              'status: '=>$book->getStatus(),
              'Quantity: '=>$book->getQuantity(),
              'isbn'=>$book->getIsbn()
          );
      }

      return $booksArray;
  }

  public function showAvailableBooks():array
  {



  $books = $this->bookRepository->getAvailableBooks();

  $booksArray=array();
  foreach($books as $book)
  {
      $booksArray[]=array(
          'id: '=>$book->getId(),
          'bookName: '=>$book->getbookName(),
          'Author: '=>$book->getAuthor(),
          'status: '=>$book->getStatus(),
          'Quantity: '=>$book->getQuantity(),
          'isbn'=>$book->getIsbn()
      );
  }
  return $booksArray;

  }

    public function showOrderedBooks(): array
    {

        $books= $this->bookRepository->getOrderedBooks();
        $booksArray=array();

        foreach($books as $book)
        {
            $booksArray[]=array(
                'id: '=>$book->getId(),
                'bookName: '=>$book->getbookName(),
                'Author: '=>$book->getAuthor(),
                'status: '=>$book->getStatus(),
                'Quantity: '=>$book->getQuantity(),
                'isbn'=>$book->getIsbn()
            );
        }

        return $booksArray;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function UpdateBook($form, $id)
    {
        $book= $this->bookRepository->find($id);
        //parsing from form:
        $this->ParseFormAndSetToEntity($form, $book);
        $errors=$this->validator->validate($book);
        if(count($errors)>0)
        {
            return new Response((string)$errors, 400);
        }
        else{

            $this->bookRepository->persist($book, true);
        }
    }

    /**
     * @param $form
     * @param $book
     * @return void
     */
    public function ParseFormAndSetToEntity($form, $book): void
    {
        //parsing form
        $bookName = $form["bookName"]->getData();
        $Quantity = $form["Quantity"]->getData();
        $Author = $form["Author"]->getData();
        $isbn = $form["isbn"]->getData();
        //setting:
        $book->setBookName($bookName);
        $book->setQuantity($Quantity);
        $book->setAuthor($Author);
        $book->setStatus("Available"); //in case of editbook it will already "Available" but for addBook we are doing this.
        $book->setIsbn($isbn);
    }

    public function setValuesById(Book $book, $id): void
    {
        //get all values from db
        $bookFromDb= $this->bookRepository->find($id);
        //setting all values to desired object "$book"
        $book->setIsbn($bookFromDb->getIsbn())->setBookName($bookFromDb->getBookName())
            ->setAuthor($bookFromDb->getAuthor())
            ->setQuantity($bookFromDb->getQuantity());
    }

    public function DeleteBook($id,$quantity): void
    {
        $this->bookRepository->deleteBook($id,$quantity);
    }

    public function BookQuantity($id):int
    {
     $book= $this->bookRepository->find($id);
        $quantity=$book->getQuantity();

        return ($quantity);
    }




}