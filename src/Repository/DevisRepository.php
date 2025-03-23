<?php

namespace App\Repository;

use App\Entity\Devis;
use App\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\soc\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }
     

    public function findByUserAndSocieteWithPagination(User $user, ?string $search, int $currentPage, int $limit)
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->leftJoin('d.society', 's') 
            ->leftJoin('s.users', 'u') 
            ->where(':user MEMBER OF s.users') 
            ->setParameter('user', $user);

        if (!empty($search)) {
            $queryBuilder->andWhere('d.ref_devis LIKE :search OR s.raison_sociale LIKE :search')
                        ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) 
            ->setMaxResults($limit); 

        return new Paginator($query, $fetchJoinCollection = true);
    }
//    /**
//     * @return Devis[] Returns an array of Devis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
