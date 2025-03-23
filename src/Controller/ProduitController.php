<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{    
        #[Route('/', name: 'app_produit_index', methods: ['GET'])]
        #[IsGranted('ROLE_USER')]
        public function index(ProduitRepository $produitRepository, Request $request): Response
        {
            $user = $this->getUser();
            $currentPage = $request->query->getInt('page', 1);
            $limit = 6; 
            $search = $request->query->get('search');

            $produits = $produitRepository->findByUserAndSocieteWithPagination($user, $search, $currentPage, $limit);
            $maxPages = ceil(count($produits) / $limit);

            $productsFound = count($produits) > 0;

            return $this->render('produit/index.html.twig', [
                'produits' => $produits,
                'maxPages' => $maxPages,
                'currentPage' => $currentPage,
                'search' => $search,
                'productsFound' => $productsFound,
            ]);
        }


        #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
        #[IsGranted('ROLE_USER')]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit, [
                'user' => $this->getUser(),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($produit);
                $entityManager->flush();

                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('produit/new.html.twig', [
                'produit' => $produit,
                'form' => $form->createView(),
            ]);
        }

        #[Route('/{slug}', name: 'app_produit_show', methods: ['GET'])]
        public function show(Produit $produit, AuthorizationCheckerInterface $authorizationChecker): Response
        {
            if (!$authorizationChecker->isGranted('view', $produit)) {
                throw $this->createAccessDeniedException('Vous n\'avez pas le droit de voir ce produit.');
            }

            return $this->render('produit/show.html.twig', [
                'produit' => $produit,
            ]);
        }

        #[Route('/{slug}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
        {
            if (!$authorizationChecker->isGranted('edit', $produit)) {
                throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier ce produit.');
            }

            $user = $this->getUser(); 

            $form = $this->createForm(ProduitType::class, $produit, [
                'user' => $user, 
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('produit/edit.html.twig', [
                'produit' => $produit,
                'form' => $form->createView(),
            ]);
        }


        #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
        public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
        {
            if (!$authorizationChecker->isGranted('delete', $produit)) {
                throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce produit.');
            }

            if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
                $entityManager->remove($produit);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
        
        #[Route('/info-by-designation/{designation}', name: 'produit_info_by_designation', methods: ['GET'])]
        #[IsGranted('ROLE_USER')]
        public function produitInfoByDesignation(string $designation, ProduitRepository $produitRepository): Response
        {
            $produit = $produitRepository->findOneBy(['designation' => $designation]);

            if (!$produit) {
                return $this->json(['error' => 'Produit non trouvé'], 404);
            }

            return $this->json([
                'designation' => $produit->getDesignation(),
                'price_unit' => $produit->getPriceUnit(),
                'taux_tva' => $produit->getTauxTva(),
            ]);
        }



        #[Route('/api/societe/{societeId}/products', name: 'api_societe_produits_list', methods: ['GET'])]
        #[IsGranted('ROLE_USER')]
        public function apiListBySociete(int $societeId, ProduitRepository $produitRepository): JsonResponse
        {
            $produits = $produitRepository->findBy(['society' => $societeId]);
            $produitsArray = [];

            foreach ($produits as $produit) {
                $produitsArray[] = [
                    'id' => $produit->getId(),
                    'designation' => $produit->getDesignation(),
                    'priceUnit' => $produit->getPriceUnit(),
                    'tauxTva' => $produit->getTauxTva(),
                ];
            }

            return $this->json($produitsArray);
        }
        
}
