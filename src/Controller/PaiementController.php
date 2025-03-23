<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Entity\Facture;
use App\Form\PaiementType;
use App\Repository\SocieteRepository;
use App\Repository\PaiementRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Service\Mailer;
use Exception;


#[Route('/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository, FactureRepository $factureRepository, Request $request , EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $currentUser = $this->getUser(); 
        $currentPage = $request->query->getInt('page', 1); 
        $limit = 6; 
        $search = $request->query->get('search'); 

        $queryBuilder = $factureRepository->createQueryBuilder('c');

        
        $selectedSociety = $request->query->get('society');
      
        $societe = $currentUser->getSocieteId();

        
        if ($request->query->has('society')) {
            $selectedSociety = $request->query->get('society');
            $session->set('selectedSociety', $selectedSociety);
        } else {
            
            $selectedSociety = $session->get('selectedSociety', null); 
        }

        if ($selectedSociety) {
            $queryBuilder->andWhere('c.society = :selectedSociety')
                        ->setParameter('selectedSociety', $selectedSociety);
        } else {
            $expr = $queryBuilder->expr();
            $orX = $expr->orX();

            foreach ($societe as $key => $s) {
                $orX->add($expr->eq('c.society', ':societe' . $key));
                $queryBuilder->setParameter('societe' . $key, $s);
            }

            $queryBuilder->andWhere($orX);
        }


        if (!empty($search)) {
            $orX = new Orx();
            $orX->add($queryBuilder->expr()->like('LOWER(c.ref_facture)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.titre_facture)', ':search'));
            
            
            $queryBuilder->andWhere($orX)
                        ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) 
            ->setMaxResults($limit); 

        $maxPages = ceil(count($paginator) / $limit); 
        $facturesFound = count($paginator) > 0;

        $factures = iterator_to_array($paginator);

        $selectedSociety = $request->query->get('society');

        foreach ($factures as $facture) {
        $paiements = $paiementRepository->findBy(['facture' => $facture]);
        $montantTotalPaye = array_reduce($paiements, function($carry, $paiement) {
            return $carry + $paiement->getMontant();
        }, 0);

        $montantRestant = $facture->getTotalTtc() - $montantTotalPaye;

        $facture->montantTotalPaye = $montantTotalPaye;
        $facture->montantRestant = $montantRestant;

        if ($montantTotalPaye == 0) {
            $facture->setStatus('Impayée');
        } elseif ($montantTotalPaye < $facture->getTotalTtc()) {
            $facture->setStatus('Partiellement payée');
            $facture->statusClass ="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600";
        } elseif ($montantTotalPaye == $facture->getTotalTtc()) {
            $facture->setStatus('Payée');
        }

        $entityManager->persist($facture);

    }

    $entityManager->flush();
        
        $paiements = $paiementRepository->findAll();

        return $this->render('paiement/index.html.twig', [
            'paiements'=>$paiements,
            'factures' => $factures,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
            'facturesFound' => $facturesFound,
            'societe' => $societe,
            'selectedSociety' => $selectedSociety,
           
        ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
    

        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
            
        ]);
    }

    #[Route('/show/{idFacture}', name: 'app_paiement_show_facture', methods: ['GET'])]
    public function showByFacture(int $idFacture, PaiementRepository $paiementRepository, FactureRepository $factureRepository): Response
    {
        $facture = $factureRepository->find($idFacture);
        if (!$facture) {
            throw $this->createNotFoundException('La facture demandée n\'existe pas.');
        }

        $paiements = $paiementRepository->findBy(['facture' => $facture]);

        $montantTotalPaye = array_reduce($paiements, function($carry, $paiement) {
            return $carry + $paiement->getMontant();
        }, 0);

        $montantRestant = $facture->getTotalTtc() - $montantTotalPaye;

        
        return $this->render('paiement/show_by_facture.html.twig', [
            'paiements' => $paiements,
            'facture' => $facture,
            'montantTotalPaye' => $montantTotalPaye,
            'montantRestant' => $montantRestant,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/send-email/{templateId}', name: 'app_paiement_send_email', methods: ['GET'])]
    public function reminderByEmail(Mailer $mailer, PaiementRepository $paiementRepository, int $id, int $templateId): Response {
        $paiement = $paiementRepository->find($id);
        if (!$paiement) {
            throw $this->createNotFoundException('Le paiement demandé n\'existe pas.');
        }
        $senderEmail = $paiement->getFacture()->getSociety()->getEmail();
        $senderName = $paiement->getFacture()->getSociety()->getRaisonSociale();
        $replyEmail = $paiement->getFacture()->getSociety()->getEmail();
        $replyName = $paiement->getFacture()->getSociety()->getRaisonSociale();

        $client = $paiement->getFacture()->getClient();
        $name = $client->getType() === 'particulier' ? $client->getNom() . ' ' . $client->getPrenom() : $client->getRaisonSociale();

        $to = [
            [
                'email' => $client->getEmail(),
                'name' => $name
            ]
        ];
        $params = [
            'client' => $name,
            'raison_sociale' => $paiement->getFacture()->getSociety()->getRaisonSociale(),
            'adresse' => $paiement->getFacture()->getSociety()->getAdresse(),
            'code' => $paiement->getFacture()->getSociety()->getCodePostal(),
            'ville' => $paiement->getFacture()->getSociety()->getVille(),
            'email' => $paiement->getFacture()->getSociety()->getEmail(),
            
        ];

        try {
            $mailer->sendPaymentReminder($templateId, $to, $params, $senderEmail, $senderName, $replyEmail, $replyName);
            return $this->render('email/email_sent_reminder.html.twig');
        } catch (Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de la relance.' . dump($e->getMessage()));
            return $this->redirectToRoute('app_paiement_show');
        }
    }


    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}