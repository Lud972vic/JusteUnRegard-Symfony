<?php

namespace App\Repository;

use App\Entity\TypeAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAnnonce[]    findAll()
 * @method TypeAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAnnonce::class);
    }

    // /**
    //  * @return TypeAnnonce[] Returns an array of TypeAnnonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('t')
    ->andWhere('t.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('t.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?TypeAnnonce
    {
    return $this->createQueryBuilder('t')
    ->andWhere('t.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
    */
}
