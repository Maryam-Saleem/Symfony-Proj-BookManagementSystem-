<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AbstractRepository
 * @package App\Repository
 */
class AbstractRepository extends ServiceEntityRepository
{
    /**
     * AbstractRepository constructor.
     * @param ManagerRegistry $registry
     * @param $entityClass
     */
    public function __construct(ManagerRegistry $registry, $entityClass = null)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param $object
     * @param bool $isFlush
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function persist($object, bool $isFlush = false): void
    {
        $this->_em->persist($object);
        if ($isFlush) {
            $this->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * @param $object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove($object): void
    {
        $object->setIsDeleted(true);
        $this->flush();
    }
}
