<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function findFacturesWithTotalPaidAndEcheance($userId, $dateDebut, $dateFin, $societeId = null)
    {
        $qb = $this->createQueryBuilder('f')
            ->select('f.id', 'f.ref_facture', 'f.total_ht', 'f.tva', 'f.total_ttc', 'f.date_echeance', 'SUM(p.montant) as totalPaid')
            ->leftJoin('f.paiements', 'p')
            ->leftJoin('f.society', 's')
            ->leftJoin('s.users', 'u')
            ->where('f.date_echeance >= :dateDebut')
            ->andWhere('u.id = :userId')
            ->groupBy('f.id')
            ->setParameter('dateDebut', $dateDebut->format('Y-m-d'))
            ->setParameter('userId', $userId);

        if ($dateFin) {
            $dateFin = $dateFin->setTime(23, 59, 59); 
            $qb->andWhere('f.date_echeance <= :dateFin')
            ->setParameter('dateFin', $dateFin);
        }

        if ($societeId) {
            $qb->andWhere('s.id = :societeId OR f.society IS NULL')
            ->setParameter('societeId', $societeId);
        } else {
            $qb->andWhere('f.society IS NULL');
        }

        return $qb->getQuery()->getResult();
    }

    public function findTopClientsWithTotalPaid($userId, $dateDebut, $dateFin = null, $societeId = null)
    {
        $qb = $this->createQueryBuilder('f')
            ->select(
                'c.id',
                'CASE WHEN c.type = \'particulier\' THEN CONCAT(c.nom, \' \', c.prenom) ELSE c.raison_sociale END AS clientName',
                'SUM(f.total_ttc) AS totalPaid'
            )
            ->leftJoin('f.client', 'c')
            ->leftJoin('f.society', 's')
            ->leftJoin('s.users', 'u')
            ->where('f.date_echeance >= :dateDebut')
            ->andWhere('u.id = :userId')
            ->andWhere('f.total_ttc IS NOT NULL')
            ->andWhere('f.status = :status') 
            ->groupBy('c.id')
            ->orderBy('totalPaid', 'DESC')
            ->setMaxResults(5)
            ->setParameter('dateDebut', $dateDebut->format('Y-m-d'))
            ->setParameter('userId', $userId)
            ->setParameter('status', 'Payée'); 

        if ($dateFin) {
            $dateFin = $dateFin->setTime(23, 59, 59);
            $qb->andWhere('f.date_echeance <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }

        if ($societeId) {
            $qb->andWhere('s.id = :societeId')
                ->setParameter('societeId', $societeId);
        }

        return $qb->getQuery()->getResult();
    }

    public function countPaidFactures($userId, $dateDebut, $dateFin = null, $societeId = null)
    {
        $qb = $this->createQueryBuilder('f')
            ->select('COUNT(f.id) as totalFactures')
            ->leftJoin('f.society', 's')
            ->leftJoin('s.users', 'u')
            ->where('f.status = :status')
            ->andWhere('u.id = :userId')
            ->andWhere('f.date_echeance >= :dateDebut')
            ->setParameter('status', 'Payée')
            ->setParameter('userId', $userId)
            ->setParameter('dateDebut', $dateDebut->format('Y-m-d'));

        if ($dateFin) {
            $qb->andWhere('f.date_echeance <= :dateFin')
            ->setParameter('dateFin', $dateFin->format('Y-m-d 23:59:59'));
        }

        if ($societeId) {
            $qb->andWhere('s.id = :societeId')
            ->setParameter('societeId', $societeId);
        } else {
            $qb->andWhere('f.society IS NOT NULL');
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
