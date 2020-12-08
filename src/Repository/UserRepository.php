<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('u')
    ->andWhere('u.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('u.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
    public function findOneBySomeField($value): ?User
    {
    return $this->createQueryBuilder('u')
    ->andWhere('u.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */

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

    public function findPinBydUser()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT user.pseudo, user.description, user.email, ville.nom, profil.profil, ville.latitude, ville.longitude FROM `user` INNER JOIN `ville` ON user.ville_id = ville.id INNER JOIN `profil` ON user.profil_id = profil.id';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
