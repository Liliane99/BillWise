<?php

namespace App\Controller;

use App\Entity\FactureProduit;
use App\Form\FactureProduitType;
use App\Repository\FactureProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facture/produit')]
class FactureProduitController extends AbstractController
{
    #[Route('/', name: 'app_facture_produit_index', methods: ['GET'])]
    public function index(FactureProduitRepository $factureProduitRepository): Response
    {
        return $this->render('facture_produit/index.html.twig', [
            'facture_produits' => $factureProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factureProduit = new FactureProduit();
        $form = $this->createForm(FactureProduitType::class, $factureProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factureProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_produit/new.html.twig', [
            'facture_produit' => $factureProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_produit_show', methods: ['GET'])]
    public function show(FactureProduit $factureProduit): Response
    {
        return $this->render('facture_produit/show.html.twig', [
            'facture_produit' => $factureProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureProduit $factureProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureProduitType::class, $factureProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_produit/edit.html.twig', [
            'facture_produit' => $factureProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_produit_delete', methods: ['POST'])]
    public function delete(Request $request, FactureProduit $factureProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureProduit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factureProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}