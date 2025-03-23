<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiement>
 *
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }
    
    public function getCAPerPeriodAndSociete($debut, $userId, $fin = null, $societeId = null) {
        $qb = $this->createQueryBuilder('p')
            ->select('SUM(p.montant) as chiffreAffaire')
            ->leftJoin('p.facture', 'f')
            ->leftJoin('f.society', 's')
            ->leftJoin('s.users', 'u')
            ->where('p.date_paiement >= :debut')
            ->andWhere('u.id = :userId')
            ->andWhere('f.status = :status') 
            ->setParameter('debut', $debut)
            ->setParameter('userId', $userId)
            ->setParameter('status', 'Payée'); 
    
        if ($fin) {
            $fin = $fin->setTime(23, 59, 59);
            $qb->andWhere('p.date_paiement <= :fin')
               ->setParameter('fin', $fin);
        }
    
        if ($societeId) {
            $qb->andWhere('f.society = :societeId')
               ->setParameter('societeId', $societeId);
        }
    
        return $qb->getQuery()->getSingleScalarResult() ?: 0;
    }
    
    

      

//    /**
//     * @return Paiement[] Returns an array of Paiement objects
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

//    public function findOneBySomeField($value): ?Paiement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
