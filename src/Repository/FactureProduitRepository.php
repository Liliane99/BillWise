<?php

namespace App\Repository;

use App\Entity\FactureProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureProduit>
 *
 * @method FactureProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureProduit[]    findAll()
 * @method FactureProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureProduit::class);
    }
    public function findTopProduitsWithTotalTTC($userId, $dateDebut, $dateFin = null, $societeId = null)
    {
        $qb = $this->createQueryBuilder('fp')
            ->select(
                'p.id',
                'p.designation AS productName',
                'SUM(fp.montant_ht) AS totalTTC'
            )
            ->join('fp.facture', 'f')
            ->join('fp.product', 'p')
            ->join('f.society', 's')
            ->join('s.users', 'u')
            ->where('f.status = :status')
            ->andWhere('f.date_echeance >= :dateDebut')
            ->andWhere('u.id = :userId')
            ->setParameter('status', 'Payée')
            ->setParameter('dateDebut', $dateDebut->format('Y-m-d'))
            ->setParameter('userId', $userId)
            ->groupBy('p.id')
            ->orderBy('totalTTC', 'DESC')
            ->setMaxResults(5);

        if ($dateFin) {
            $dateFin = $dateFin->setTime(23, 59, 59);
            $qb->andWhere('f.date_echeance <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }

        if ($societeId) {
            $qb->andWhere('s.id = :societeId')
                ->setParameter('societeId', $societeId);
        } else {
            // Si aucune société spécifique n'est requise, retirez cette condition ou ajustez selon la logique de votre application.
        }

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return FactureProduit[] Returns an array of FactureProduit objects
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

//    public function findOneBySomeField($value): ?FactureProduit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
