<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    // /**
    //  * @return Media[] Returns an array of Media objects
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
    public function findOneBySomeField($value): ?Media
    {
    return $this->createQueryBuilder('m')
    ->andWhere('m.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */

    /*Chaque utilisateur, ne voit que ses médias*/
    public function findMediaByIdUser($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $value)
            ->orderBy('m.updated_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    /*On récupère le nombre de média, qu'on souhaite afficher par rapport à leur extension "image/jpeg Ou video/mp4"*/
    public function searchMedia($filtre, $nombre)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.type_fichier LIKE :searchFiltre')
            ->andWhere('m.banni is null or m.banni != 1')
            ->setParameter('searchFiltre', '%' . $filtre . '%')
            ->orderBy('RAND()')
            ->setMaxResults($nombre)
            ->getQuery()
            ->execute();
    }
    /*On récupère tous les médias, qu'on souhaite afficher par rapport à leur extension "image/jpeg Ou video/mp4"*/
    public function searchAllMedia($filtre)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.type_fichier LIKE :searchFiltre')
            ->setParameter('searchFiltre', '%' . $filtre . '%')
            ->orderBy('RAND()')
            ->getQuery()
            ->execute();
    }
    /*On récupère tous les médias, qu'on souhaite afficher par rapport à leur extension "image/jpeg Ou video/mp4"*/
    public function likeAllMedia($filtre)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.libelle LIKE :searchFiltre')
            ->setParameter('searchFiltre', '%' . $filtre . '%')
            ->orderBy('RAND()')
            ->getQuery()
            ->execute();
    }

        /*On récupère tous les médias, qu'on souhaite afficher par rapport à des mots-clés"*/
        /*Sont exclus les médias bannis*/
        public function findAllMedia($motsCles)
        {
            return $this->createQueryBuilder('m')
                ->andWhere('m.libelle LIKE :searchFiltre OR m.description LIKE :searchFiltre')
                ->andWhere('m.banni <> 1')
                ->setParameter('searchFiltre', '%' . $motsCles . '%')
                ->orderBy('m.type_fichier')
                ->getQuery()
                ->execute();
        }

    /*On affiche tous les médias d'une catégorie*/
    public function findMediaByIdCategory($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.categorie  = :val')
            ->setParameter('val', $value)
            ->orderBy('m.updated_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
