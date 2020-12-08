<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Retourne une liste d'article pour l'API
     * @return array
     */
    public function apiFindAll(): array
    {
        $queryBuilder = $this->createQueryBuilder('article')
            ->select('article.id', 'article.titre', 'article.contenu', 'article.image', 'article.created_at')
            ->orderBy('article.created_at', 'DESC');

        $query = $queryBuilder->getQuery();
        return $query->execute();
    }

    /**
     * Retourne un article pour l'API
     * @param $id
     * @return array
     */
    public function apiFindOneBy($id): array
    {
        $queryBuilder = $this->createQueryBuilder('article')
            ->select('article.id', 'article.titre', 'article.contenu', 'article.image', 'article.created_at')
            ->where('article.id = ' . $id);

        $query = $queryBuilder->getQuery();
        return $query->execute();
    }

    /*Chaque utilisateur, ne voit que ses articles*/
    public function findArticleByIdUser($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $value)
            ->orderBy('a.updated_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
public function findOneBySomeField($value): ?Article
{
return $this->createQueryBuilder('a')
->andWhere('a.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
