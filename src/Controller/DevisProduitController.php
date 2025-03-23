<?php

namespace App\Controller;

use App\Entity\DevisProduit;
use App\Form\DevisProduitType;
use App\Repository\DevisProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis/produit')]
class DevisProduitController extends AbstractController
{
    #[Route('/', name: 'app_devis_produit_index', methods: ['GET'])]
    public function index(DevisProduitRepository $devisProduitRepository): Response
    {
        return $this->render('devis_produit/index.html.twig', [
            'devis_produits' => $devisProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_devis_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devisProduit = new DevisProduit();
        $form = $this->createForm(DevisProduitType::class, $devisProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($devisProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis_produit/new.html.twig', [
            'devis_produit' => $devisProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_produit_show', methods: ['GET'])]
    public function show(DevisProduit $devisProduit): Response
    {
        return $this->render('devis_produit/show.html.twig', [
            'devis_produit' => $devisProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DevisProduit $devisProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevisProduitType::class, $devisProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis_produit/edit.html.twig', [
            'devis_produit' => $devisProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_produit_delete', methods: ['POST'])]
    public function delete(Request $request, DevisProduit $devisProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devisProduit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devisProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
