<?php

namespace App\Repository;

use App\Entity\soc\Societe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Societe>
 *
 * @method Societe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societe[]    findAll()
 * @method Societe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

//    /**
//     * @return Societe[] Returns an array of Societe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findByCriteriaWithPagination(User $user, ?string $search, int $currentPage, int $limit)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId());

        if (!empty($search)) {
            $queryBuilder->andWhere('s.raison_sociale LIKE :search')
                        ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder->getQuery()
            ->setFirstResult($limit * ($currentPage - 1))
            ->setMaxResults($limit);

        return new \Doctrine\ORM\Tools\Pagination\Paginator($query, $fetchJoinCollection = true);
    }
    public function findAllAccessibleSocietes(User $user)
    {
        // Retourne toutes les sociétés accessibles par l'utilisateur courant
        return $this->createQueryBuilder('s')
            ->innerJoin('s.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Societe
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
