<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\NoReturn;

/**
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
public function getAvailableBooks():array
{
      $queryBuilder=$this->createQueryBuilder('b')->where('b.status =:status')->setParameter('status','Available');
      $query=$queryBuilder->getQuery();
      return $query->execute();
}
public function getOrderedBooks():array //Books users have ordered
{
    $queryBuilder=$this->createQueryBuilder('b')->where('b.status=:status')->setParameter('status', 'NotAvailable');
    return $queryBuilder->getQuery()->execute();
}

public function deleteBook($id, $quantity):void
{

    $qb=$this->createQueryBuilder('b');
    $qb->update()->set('b.Quantity',$qb->expr()->diff('b.Quantity',1))
        ->where($qb->expr()->andX('b.Quantity > :quantity' , 'b.id=:id'))
        ->setParameters(['quantity'=>0 , 'id'=>$id]);

    if ($quantity <= 1) {

        $qb->update()->set('b.status',':status')
        ->setParameter( 'status','NotAvailable');
    }

    $qb->getQuery()->execute();

}




//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
