<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\soc\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findByUserAndSocieteWithPagination(User $user, ?string $search, int $currentPage, int $limit)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.society', 's') 
            ->leftJoin('s.users', 'u') 
            ->where(':user MEMBER OF s.users') 
            ->setParameter('user', $user);

        if (!empty($search)) {
            $queryBuilder->andWhere('p.designation LIKE :search OR p.categorie LIKE :search OR s.raison_sociale LIKE :search')
                        ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) 
            ->setMaxResults($limit); 

        return new Paginator($query, $fetchJoinCollection = true);
    }

    public function countAllProducts($userId, $societeId = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as totalProducts')
            ->leftJoin('p.society', 's')
            ->leftJoin('s.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        if ($societeId) {
            $qb->andWhere('s.id = :societeId')
               ->setParameter('societeId', $societeId);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }


//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
