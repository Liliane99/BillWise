<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\SocieteRepository;
use Exception;
use App\Entity\soc\Societe;
use App\Service\Mailer;
use App\Entity\FactureProduit;
use App\Form\FactureType;
use TCPDF;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/facture', name: 'app_facture')]
class FactureController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, Request $request): Response
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
            $orX->add($queryBuilder->expr()->like('Lower(c.condition)', ':search'));
            

            
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
        

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
            'facturesFound' => $facturesFound,
            'societe' => $societe,
            'selectedSociety' => $selectedSociety,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
  
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        

        $facture = new Facture();
        $user = $this->getUser();
        $userSocietes = $user->getSocieteId()->toArray();

        $form = $this->createForm(FactureType::class, $facture , [
            'user_societes' => $userSocietes,
        ]);
        $form->handleRequest($request);

        $entityManager = $this->doctrine->getManager();

        $produitRepository = $this->doctrine->getRepository(Produit::class);

        $produits = $produitRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $facture = $form->getData();
            $factureProduits = $form->get('factureProduits')->getData();
            foreach ($factureProduits as $factureProduit) {
                $factureProduit->setFacture($facture);
                $entityManager->persist($factureProduit);
            }

            
            
            $entityManager->persist($facture);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_facture_show', ['id' => $facture->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
            'produits' => $produits,
            
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    #[ParamConverter("facture", class:"App\Entity\Facture")]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
        $userSocietes = $user->getSocieteId()->toArray(); 

        $form = $this->createForm(FactureType::class, $facture, [
            'user_societes' => $userSocietes,
        ]);
        $form->handleRequest($request);

        // Récupérer le repository des produits
        $produitRepository = $this->doctrine->getRepository(Produit::class);

        // Récupérer tous les produits depuis la base de données
        $produits = $produitRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
            'factureProduits' => $facture->getFactureProduits(),
            'produits'=>$produits,
        ]);
    }

    #[Route('facture/{id}/send-email/{templateId}', name: 'app_facture_send_email', methods: ['GET'])]
    public function sendFactureByEmail(Mailer $mailer, Facture $facture, int $templateId): Response {
        $pdfContent = $this->generatePdf($facture);
        // $templateId = $this->parameterBag->get('sendinblue_template_id');
        $pdfName = 'Facture_' . $facture->getId() . '.pdf';
        $senderEmail = $facture->getSociety()->getEmail();
        $senderName = $facture->getSociety()->getRaisonSociale();
        $replyEmail = $facture->getSociety()->getEmail();
        $replyName = $facture->getSociety()->getRaisonSociale();

        $client = $facture->getClient();
        $name = $client->getType() === 'particulier' ? $client->getNom() . ' ' . $client->getPrenom() : $client->getRaisonSociale();

        $to = [
            [
                'email' => $client->getEmail(),
                'name' => $name
            ]
        ];
        $params = [
            'client' => $name,
            'raison_sociale' => $facture->getSociety()->getRaisonSociale(),
            'adresse' => $facture->getSociety()->getAdresse(),
            'code' => $facture->getSociety()->getCodePostal(),
            'ville' => $facture->getSociety()->getVille(),
            'email' => $facture->getClient()->getEmail(),
            
        ];

        try {
            $mailer->sendTemplateWithAttachment($templateId, $to, $params, $pdfContent, $pdfName, $senderEmail, $senderName, $replyEmail, $replyName);
            return $this->render('email/email_fact_success.html.twig');
        } catch (Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de la facture.'. $e->getMessage());
            return $this->redirectToRoute('app_facture_show', ['id' => $facture->getId()]);
        }
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/facture/{id}/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(Facture $facture): Response
    {
        try {
            $html = $this->renderView('facture/pdf_template.html.twig', [
                'facture' => $facture,
            ]);
            
            $dompdf = new Dompdf();
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf->setOptions($options);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            $dompdf->render();

            $projectDir = $this->getParameter('kernel.project_dir');
            $logoFilename = $facture->getSociety()->getAvatarLogo();
            $logoPath = $logoFilename ? $projectDir . '/public/uploads/societe/avatar_logo/' . $logoFilename : $projectDir . '/public/build/images/default_logo.png'; // Chemin par défaut si aucun logo

            // dump($logoPath);

            $societeInfos = [
            'raison_sociale' => $facture->getSociety()->getRaisonSociale(),
            'adresse' => $facture->getSociety()->getAdresse(),
            'code_postal' => $facture->getSociety()->getCodePostal(),
            'ville' => $facture->getSociety()->getVille(),
            'email' => $facture->getSociety()->getEmail(), 
            'num_tel' => $facture->getSociety()->getNumTel(),
            'num_siret' => $facture->getSociety()->getNumSiret(),
            'logoPath' => $logoPath,
            ];

            $clientInfos = [
                'nom' => $facture->getClient()->getNom(),
                'prenom' => $facture->getClient()->getPrenom(),
                'email' => $facture->getClient()->getEmail(),
                'adresse' => $facture->getClient()->getAdresse(),
                'code_postal' => $facture->getClient()->getCodePostal(),
                'ville' => $facture->getClient()->getVille(),
                'raison_sociale' => $facture->getClient()->getRaisonSociale(),
                'type' => $facture->getClient()->getType(),
            ];

            $html = $this->renderView('facture/pdf_template.html.twig', [
                'facture' => $facture,
                'societeInfos' => $societeInfos,
                'clientInfos' => $clientInfos,
            ]);
            
            return new Response(
                $dompdf->output(),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => sprintf('inline; filename="facture_%s.pdf"', $facture->getId()),
                ]
            );
        } catch (Exception $e) {
            return new Response('Erreur lors de la génération du PDF : ' . $e->getMessage(), 500);
        }
    }

    #[Route('/facture-info/{id}', name: 'facture_info', methods: ['GET'])]
    public function getfactureInfo(FactureRepository $repository, int $id): JsonResponse
    {
        $facture = $repository->find($id);
        
        if (!$facture) {
            return new JsonResponse(['error' => 'Facture non trouvé'], 404);
        }

        $data = [
            'titre' => $facture->getTitreFacture(),
            'client' => $facture->getClient()->getNom() . ' ' . $facture->getClient()->getPrenom()
        ];

        return new JsonResponse($data);
    }
}