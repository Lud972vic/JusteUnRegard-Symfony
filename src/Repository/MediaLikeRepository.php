<?php

namespace App\Repository;

use App\Entity\MediaLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaLike[]    findAll()
 * @method MediaLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaLike::class);
    }

    // /**
    //  * @return MediaLike[] Returns an array of MediaLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('m')
    ->andWhere('m.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('m.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?MediaLike
    {
    return $this->createQueryBuilder('m')
    ->andWhere('m.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
    */
}
