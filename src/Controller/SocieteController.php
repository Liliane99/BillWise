<?php

namespace App\Controller;

use App\Entity\soc\Societe;
use App\Form\SocieteType;
use App\Repository\SocieteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/societe')]
class SocieteController extends AbstractController
{
    #[Route('/', name: 'app_societe_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(SocieteRepository $societeRepository, Request $request): Response
    {
        $user = $this->getUser();
        $currentPage = $request->query->getInt('page', 1);
        $limit = 6;
        $search = $request->query->get('search');

        $societes = $societeRepository->findByCriteriaWithPagination($user, $search, $currentPage, $limit);
        $maxPages = ceil(count($societes) / $limit);

        return $this->render('societe/index.html.twig', [
            'societes' => $societes,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'app_societe_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser(); 

            if ($user) {
                $societe->addUser($user); 
            }

            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('societe/new.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_societe_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(Societe $societe): Response
    {
        return $this->render('societe/show.html.twig', [
            'societe' => $societe,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_societe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('societe/edit.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_societe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($societe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/societe-info/{id}', name: 'societe_info', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getSocieteInfo(SocieteRepository $repository, int $id): JsonResponse
    {
        $societe = $repository->find($id);
        
        if (!$societe) {
            return new JsonResponse(['error' => 'Société non trouvée'], 404);
        }

        $data = [
            'raison_sociale' => $societe->getRaisonSociale(),
            'adresse' => $societe->getAdresse(),
            'code_postal' => $societe->getCodePostal(),
            'ville' => $societe->getVille(),
            'num_tel' => $societe->getNumTel(),
        ];

        return new JsonResponse($data);
    }
    #[Route('/api/societe/{id}/clients', name: 'api_societe_clients')]
    #[IsGranted('ROLE_USER')]
    public function getClientsBySociete(Societe $societe): JsonResponse
    {
        $clients = [];
        foreach ($societe->getClients() as $client) {
            $label = $client->getType() === 'entreprise' 
                    ? $client->getRaisonSociale() 
                    : $client->getNom() . ' ' . $client->getPrenom();
            $clients[] = [
                'id' => $client->getId(),
                'label' => $label, 
            ];
        }
        return $this->json($clients);
    }

}
