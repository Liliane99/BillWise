<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Service\Mailer;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


#[Route('/devis')]
class DevisController extends AbstractController
{
    #[Route('/', name: 'app_devis_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(DevisRepository $devisRepository, Request $request): Response
    {
        $user = $this->getUser();
        $currentPage = $request->query->getInt('page', 1);
        $limit = 6; 
        $search = $request->query->get('search');

        $devis = $devisRepository->findByUserAndSocieteWithPagination($user, $search, $currentPage, $limit);
        $maxPages = ceil(count($devis) / $limit);

        $devisFound = count($devis) > 0;

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
            'devisFound' => $devisFound,
        ]);
    }


    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devi = new Devis();
        $user = $this->getUser(); 
        $userSocietes = $user->getSocieteId()->toArray();
        
        $form = $this->createForm(DevisType::class, $devi, [
            'user_societes' => $userSocietes,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($devi);
            $entityManager->flush();
            return $this->redirectToRoute('app_devis_show', ['slug' => $devi->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}/show', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('view', $devi)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de voir ce devis.');
        }

        return $this->render('devis/show.html.twig', ['devi' => $devi]);
    }

    #[Route('/{slug}/edit', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('edit', $devi)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier ce devis.');
        }

        $form = $this->createForm(DevisType::class, $devi, ['user_societes' => $this->getUser()->getSocieteId()->toArray()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_devis_index');
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi, 
            'form' => $form->createView(),
            'devisProduits' => $devi->getDevisProduits(),
        ]);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('delete', $devi)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce devis.');
        }

        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_index');
    }

   
    #[Route('/{id}/pdf', name: 'app_devis_pdf', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function generatePdf(Devis $devi, Packages $assetsManager): Response
    {
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Votre Nom');
        $pdf->SetTitle('Devis ' . $devi->getRefDevis());
        $pdf->SetSubject('Devis');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $projectDir = $this->getParameter('kernel.project_dir');
        $logoFilename = $devi->getSociety()->getAvatarLogo();
        $logoPath = $logoFilename ? $projectDir . '/public/uploads/societe/avatar_logo/' . $logoFilename : $projectDir . '/public/build/images/default_logo.png'; 

        // dump($logoPath);

        $societeInfos = [
            'raison_sociale' => $devi->getSociety()->getRaisonSociale(),
            'adresse' => $devi->getSociety()->getAdresse(),
            'code_postal' => $devi->getSociety()->getCodePostal(),
            'ville' => $devi->getSociety()->getVille(),
            'email' => $devi->getSociety()->getEmail(), 
            'num_tel' => $devi->getSociety()->getNumTel(),
            'num_siret' => $devi->getSociety()->getNumSiret(),
            'logoPath' => $logoPath,
        ];

        $clientInfos = [
            'nom' => $devi->getClient()->getNom(),
            'prenom' => $devi->getClient()->getPrenom(),
            'email' => $devi->getClient()->getEmail(),
            'adresse' => $devi->getClient()->getAdresse(),
            'code_postal' => $devi->getClient()->getCodePostal(),
            'ville' => $devi->getClient()->getVille(),
            'raison_sociale' => $devi->getClient()->getRaisonSociale(),
            'type' => $devi->getClient()->getType(),
        ];

        $html = $this->renderView('devis/devis_pdf.html.twig', [
            'devi' => $devi,
            'societeInfos' => $societeInfos,
            'clientInfos' => $clientInfos,
        ]);

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdfOutput = $pdf->Output('devis_' . $devi->getId() . '.pdf', 'S');
        $response = new Response($pdfOutput);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="devis_' . $devi->getId() . '.pdf"');
        return $response;
    }

    #[Route('/{id}/pdff', name: 'app_devis_pdff', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function generatePdfContent(Devis $devi): string 
    {
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Votre Nom');
        $pdf->SetTitle('Devis ' . $devi->getRefDevis());
        $pdf->SetSubject('Devis');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $projectDir = $this->getParameter('kernel.project_dir');
        $logoFilename = $devi->getSociety()->getAvatarLogo();
        $logoPath = $logoFilename ? $projectDir . '/public/uploads/societe/avatar_logo/' . $logoFilename : $projectDir . '/public/build/images/default_logo.png'; // Chemin par défaut si aucun logo

        // dump($logoPath);

        $societeInfos = [
            'raison_sociale' => $devi->getSociety()->getRaisonSociale(),
            'adresse' => $devi->getSociety()->getAdresse(),
            'code_postal' => $devi->getSociety()->getCodePostal(),
            'ville' => $devi->getSociety()->getVille(),
            'email' => $devi->getSociety()->getEmail(), 
            'num_tel' => $devi->getSociety()->getNumTel(),
            'num_siret' => $devi->getSociety()->getNumSiret(),
            'logoPath' => $logoPath,
        ];

        $clientInfos = [
            'nom' => $devi->getClient()->getNom(),
            'prenom' => $devi->getClient()->getPrenom(),
            'email' => $devi->getClient()->getEmail(),
            'adresse' => $devi->getClient()->getAdresse(),
            'code_postal' => $devi->getClient()->getCodePostal(),
            'ville' => $devi->getClient()->getVille(),
            'raison_sociale' => $devi->getClient()->getRaisonSociale(),
            'type' => $devi->getClient()->getType(),
        ];

        $html = $this->renderView('devis/devis_pdf.html.twig', [
            'devi' => $devi,
            'societeInfos' => $societeInfos,
            'clientInfos' => $clientInfos,
        ]);

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdfOutput = $pdf->Output('devis_' . $devi->getId() . '.pdf', 'S');
        $response = new Response($pdfOutput);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="devis_' . $devi->getId() . '.pdf"');
        $pdfOutput = $pdf->Output('devis_' . $devi->getId() . '.pdf', 'S');
        return $pdfOutput;
    }


    
    #[Route('/{id}/send-email/{templateId}', name: 'app_devis_send_email', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function sendDevisByEmail(Mailer $mailer, Devis $devi, int $templateId): Response {
        $pdfContent = $this->generatePdfContent($devi);
        // $templateId = $this->parameterBag->get('sendinblue_template_id');
        $pdfName = 'devis_' . $devi->getId() . '.pdf';
        $senderEmail = $devi->getSociety()->getEmail();
        $senderName = $devi->getSociety()->getRaisonSociale();
        $replyEmail = $devi->getSociety()->getEmail();
        $replyName = $devi->getSociety()->getRaisonSociale();

        $client = $devi->getClient();
        $name = $client->getType() === 'particulier' ? $client->getNom() . ' ' . $client->getPrenom() : $client->getRaisonSociale();

        $to = [
            [
                'email' => $client->getEmail(),
                'name' => $name
            ]
        ];
        $params = [
            'client' => $name,
            'raison_sociale' => $devi->getSociety()->getRaisonSociale(),
            'adresse' => $devi->getSociety()->getAdresse(),
            'code' => $devi->getSociety()->getCodePostal(),
            'ville' => $devi->getSociety()->getVille(),
            'email' => $devi->getSociety()->getEmail(),
            
        ];

        try {
            $mailer->sendTemplateWithAttachment($templateId, $to, $params, $pdfContent, $pdfName, $senderEmail, $senderName, $replyEmail, $replyName);
            return $this->render('email/email_sent_success.html.twig');
        } catch (Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du devis.');
            return $this->redirectToRoute('app_devis_show', ['id' => $devi->getId()]);
        }
    }

}
