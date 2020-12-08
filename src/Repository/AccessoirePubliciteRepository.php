<?php

namespace App\Repository;

use App\Entity\AccessoirePublicite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessoirePublicite|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessoirePublicite|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessoirePublicite[]    findAll()
 * @method AccessoirePublicite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessoirePubliciteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessoirePublicite::class);
    }

    // /**
    //  * @return AccessoirePublicite[] Returns an array of AccessoirePublicite objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('a')
    ->andWhere('a.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('a.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?AccessoirePublicite
    {
    return $this->createQueryBuilder('a')
    ->andWhere('a.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */

    /*Chaque utilisateur, ne voit que ses médias*/
    public function findAccessoirePubliciteByIdUser($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $value)
            ->orderBy('a.updated_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*On récupère tous les accessoires ou toutes les poublicités, qu'on souhaite afficher par rapport à leur type*/
    public function searchAllAccessoirePublicite($filtre)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.typeannonce = :searchFiltre')
            ->setParameter('searchFiltre', $filtre)
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->execute();
    }
}
