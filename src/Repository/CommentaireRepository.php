<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    // /**
    //  * @return Commentaire[] Returns an array of Commentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('c')
    ->andWhere('c.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('c.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Commentaire
    {
    return $this->createQueryBuilder('c')
    ->andWhere('c.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */

    /*On récupère les 10 derniers commentaires du média*/
    public function findCommentByMedia($value)
    {
        return $this->createQueryBuilder('c')
            ->Select('c.created_at', 'c.contenu', 'u.pseudo')
            ->andWhere('c.media = :val')
            ->setParameter('val', $value)
            ->leftJoin(
                'App:User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.user = u.id'
            )
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
