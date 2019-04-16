<?php

namespace App\Repository;

use App\Entity\ItemStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemStatus[]    findAll()
 * @method ItemStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemStatus::class);
    }

    // /**
    //  * @return ItemStatus[] Returns an array of ItemStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemStatus
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
