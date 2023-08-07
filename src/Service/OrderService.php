<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\BookRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\SecurityBundle\Security;

class OrderService
{
    private OrderRepository $orderRepository;
    private Security $security;
    private BookRepository $bookRepository;
    private UserRepository $userRepository;

public function __construct(OrderRepository $orderRepository, Security $security, BookRepository $bookRepository, UserRepository $userRepository){
    $this->orderRepository=$orderRepository;
    $this->security=$security;
    $this->bookRepository=$bookRepository;
    $this->userRepository=$userRepository;
}

    public function showAllCustomers(): array
    {
        $orders=$this->orderRepository->findAll();
        $booksArray=array();

        foreach($orders as $order)
        {
            $booksArray[]=array(
                'OrderId: '=>$order->getId(),
                'UserId: '=>$order->getUserId()->getId(),
                'UserName: '=>$order->getUserId()->getFirstName(),
                'BookId: '=>$order->getOrderedBookId()->getId(),
                'BookIsbn: '=>$order->getOrderedBookId()->getIsbn(),
                'BookName: '=>$order->getOrderedBookId()->getBookName()
            );
        }

        return $booksArray;

    }

    public function showAllSoldBooks(): array
    {

        $orders=$this->orderRepository->findAll();
        $booksArray=array();

        foreach($orders as $order)
        {
            $booksArray[]=array(
                'OrderId: '=>$order->getId(),
                'BookId: '=>$order->getOrderedBookId()->getId(),
                'BookIsbn: '=>$order->getOrderedBookId()->getIsbn(),
                'BookName: '=>$order->getOrderedBookId()->getBookName()
            );
        }

        return $booksArray;
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function addOrder($bookId): void
    {

        $userId=null;
        $user=$this->security->getUser();

        if($user)
        {
            $userId=$user->getId();
        }

        $userEntity= $this->userRepository->find($userId);
        $bookEntity= $this->bookRepository->find($bookId);
        $order=new Order();

        if($bookEntity && $userEntity)
        {
            $order->setUserId($userEntity);
            $order->setOrderedBookId($bookEntity);

            $this->orderRepository->persist($order, true);
        }

    }

    public function showCustomerOrderHistory(): array
    {
        $userId=null;
        $user=$this->security->getUser();

        if($user)
        {
            $userId=$user->getId();
        }

        $userOrders=$this->orderRepository->findAll($userId);
        $userOrdersArray=[];

        foreach($userOrders as $userOrder)
        {
            $userOrdersArray[]=array(
                'orderId: '=>$userOrder->getId(),
                'orderedBookId: '=>$userOrder->getOrderedBookId()->getId(),
                'BookName: '=>$userOrder->getOrderedBookId()->getBookName()
            );
        }

        return ($userOrdersArray);
    }
}