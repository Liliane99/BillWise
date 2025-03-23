<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PaiementRepository;
use App\Repository\FactureRepository;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use App\Repository\FactureProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_COMPTABLE')]
    public function dashboard(Request $request, PaiementRepository $paiementRepo, FactureRepository $factureRepo): Response
    {
        $societeId = $request->query->get('societeId');
        $dateDebut = $request->query->get('startDate') ? new \DateTimeImmutable($request->query->get('startDate')) : new \DateTimeImmutable('first day of this month');
        $dateFin = $request->query->get('endDate') ? new \DateTimeImmutable($request->query->get('endDate')) : new \DateTimeImmutable();

        $userId = $this->getUser()->getId();

        $factures = $factureRepo->findFacturesWithTotalPaidAndEcheance($userId, $dateDebut, $dateFin, $societeId);

        return $this->render('dashboard/dashboard.html.twig', [
            'factures' => $factures,
            'societeId' => $societeId,
            'startDate' => $dateDebut->format('Y-m-d'),
            'endDate' => $dateFin->format('Y-m-d'),
        ]);
    }

    #[Route('/api/chiffre-affaire', name: 'api_chiffre_affaire', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function chiffreAffaire(Request $request, PaiementRepository $paiementRepo): Response
    {
        $data = json_decode($request->getContent(), true);
        $dateDebut = new \DateTimeImmutable($data['startDate']);
        $dateFin = new \DateTimeImmutable($data['endDate']);
        $societeId = $data['societeId'] ?? null;
        $userId = $this->getUser()->getId();

        $chiffreAffaire = $paiementRepo->getCAPerPeriodAndSociete($dateDebut, $userId, $dateFin, $societeId);
        $chiffreAffaireTotal = $paiementRepo->getCAPerPeriodAndSociete(new \DateTimeImmutable('@0'), $userId, null, $societeId);

        return $this->json([
            'chiffreAffaire' => $chiffreAffaire,
            'chiffreAffaireTotal' => $chiffreAffaireTotal
        ]);
    }

    #[Route('/api/factures', name: 'api_factures', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function factures(Request $request, FactureRepository $factureRepo): Response
    {
        $data = json_decode($request->getContent(), true);
        $dateDebut = new \DateTimeImmutable($data['startDate']);
        $dateFin = new \DateTimeImmutable($data['endDate']);
        $societeId = $data['societeId'] ?? null;
        $userId = $this->getUser()->getId();

        $societesId = $this->getUser()->getSocieteId(); 

        
        if (!$societeId) {
            $factures = [];
            foreach ($societesId as $societeId) {
                $factures = array_merge($factures, $factureRepo->findFacturesWithTotalPaidAndEcheance($userId, $dateDebut, $dateFin, $societeId));
            }
        } else {
            $factures = $factureRepo->findFacturesWithTotalPaidAndEcheance($userId, $dateDebut, $dateFin, $societeId);
        }

        $formattedFactures = [];
        foreach ($factures as $facture) {
            $formattedFactures[] = [
                'refFacture' => $facture['ref_facture'],
                'totalHt' => $facture['total_ht'],
                'tva' => $facture['tva'],
                'totalTtc' => $facture['total_ttc'],
                'totalPaid' => $facture['totalPaid'],
                'dateEcheance' => $facture['date_echeance']->format('Y-m-d'), 
            ];
        }

        return $this->json(['factures' => $formattedFactures]);
    }

    #[Route('/api/top-clients', name: 'api_top_clients', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function topClients(Request $request, FactureRepository $factureRepo): Response
    {
        $data = json_decode($request->getContent(), true);
        $dateDebut = new \DateTimeImmutable($data['startDate']);
        $dateFin = isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null;
        $societeId = $data['societeId'] ?? null;
        $userId = $this->getUser()->getId();

        $topClients = $factureRepo->findTopClientsWithTotalPaid($userId, $dateDebut, $dateFin, $societeId);

        return $this->json(['topClients' => $topClients]);
    }
    #[Route('/api/top-produits', name: 'api_top_produits', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function topProduits(Request $request, FactureProduitRepository $factureProduitRepo): Response
    {
        $data = json_decode($request->getContent(), true);
        $dateDebut = new \DateTimeImmutable($data['startDate']);
        $dateFin = isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null;
        $societeId = $data['societeId'] ?? null;
        $userId = $this->getUser()->getId();

        $topProduits = $factureProduitRepo->findTopProduitsWithTotalTTC($userId, $dateDebut, $dateFin, $societeId);

        return $this->json(['topProduits' => $topProduits]);
    }

    #[Route('/api/count-paid-factures', name: 'api_count_paid_factures', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function countPaidFactures(Request $request, FactureRepository $factureRepo): Response
    {
        $data = json_decode($request->getContent(), true);
        $dateDebut = new \DateTimeImmutable($data['startDate']);
        $dateFin = isset($data['endDate']) ? new \DateTimeImmutable($data['endDate']) : null;
        $societeId = $data['societeId'] ?? null;
        $userId = $this->getUser()->getId();

        $totalPaidFactures = $factureRepo->countPaidFactures($userId, $dateDebut, $dateFin, $societeId);

        return $this->json(['totalPaidFactures' => $totalPaidFactures]);
    }
    #[Route('/api/count-clients', name: 'api_count_clients', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function countClients(Request $request, ClientRepository $clientRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $this->getUser()->getId();
        $societeId = $data['societeId'] ?? null;

        $totalClients = $clientRepo->countAllClients($userId, $societeId);

        return $this->json(['totalClients' => $totalClients]);
    }

    #[Route('/api/count-products', name: 'api_count_products', methods: ['POST'])]
    #[IsGranted('ROLE_COMPTABLE')]
    public function countProducts(Request $request, ProduitRepository $produitRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $this->getUser()->getId();
        $societeId = $data['societeId'] ?? null;

        $totalProducts = $produitRepo->countAllProducts($userId, $societeId);

        return $this->json(['totalProducts' => $totalProducts]);
    }


}
