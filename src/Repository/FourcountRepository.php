<?php

namespace App\Repository;

use App\Entity\Fourcount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fourcount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fourcount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fourcount[]    findAll()
 * @method Fourcount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FourcountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fourcount::class);
    }

    // /**
    //  * @return Fourcount[] Returns an array of Fourcount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fourcount
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
