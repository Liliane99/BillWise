<?php

namespace App\Controller;
use App\Entity\User; 
use App\Entity\soc\Societe;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Orx;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $session = $request->getSession();
        $currentUser = $this->getUser(); // Récupère l'utilisateur actuellement connecté
        $currentPage = $request->query->getInt('page', 1); // La page actuelle, avec 1 comme valeur par défaut
        $limit = 6; // Indique le nombre de client à afficher sur une seule page
        $search = $request->query->get('search'); // Récupère la chaîne de recherche

        $queryBuilder = $clientRepository->createQueryBuilder('c');

        // Dans votre méthode index du contrôleur
        $selectedSociety = $request->query->get('society');
        // Filtrer les clients par la société de l'utilisateur connecté
        $societe = $currentUser->getSocieteId();

        
        // Vérifiez si une société est sélectionnée dans la requête et mettez à jour la session
        if ($request->query->has('society')) {
            $selectedSociety = $request->query->get('society');
            $session->set('selectedSociety', $selectedSociety);
        } else {
            // Si aucune société n'est sélectionnée dans la requête, utilisez la valeur de la session
            $selectedSociety = $session->get('selectedSociety', null); // Utilisez null ou une valeur par défaut appropriée
        }
        // Filtrer les clients par la société sélectionnée si elle est définie
        if ($selectedSociety) {
            $queryBuilder->andWhere('c.society = :selectedSociety')
                        ->setParameter('selectedSociety', $selectedSociety);
        } else {
            // Si aucune société n'est sélectionnée, filtre par les sociétés de l'utilisateur connecté
            $expr = $queryBuilder->expr();
            $orX = $expr->orX();

            foreach ($societe as $key => $s) {
                $orX->add($expr->eq('c.society', ':societe' . $key));
                $queryBuilder->setParameter('societe' . $key, $s);
            }

            $queryBuilder->andWhere($orX);
        }

        /*$queryBuilder->andWhere('c.society = :societe')
                 ->setParameter('societe', $societe);*/

        /*if ($currentUser instanceof User && in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            // Filtrer les utilisateurs créés par l'administrateur connecté
            $queryBuilder->where('c.creator = :creator')
                        ->setParameter('creator', $currentUser);
        }*/

        if (!empty($search)) {
            // Appliquer un filtre de recherche si une chaîne de recherche est fournie
            $orX = new Orx();
            $orX->add($queryBuilder->expr()->like('LOWER(c.email)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.nom)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.prenom)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.type)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.raison_sociale)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.num_tel)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.num_fix)', ':search'));
            $orX->add($queryBuilder->expr()->like('Lower(c.num_siret)', ':search'));
            
            $queryBuilder->andWhere($orX)
                        ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) // Définir l'offset
            ->setMaxResults($limit); // Définir la limite

        $maxPages = ceil(count($paginator) / $limit); // Calculer le nombre total de pages
        $clientsFound = count($paginator) > 0;

        $clients = iterator_to_array($paginator);

      // Dans votre méthode index du contrôleur
        $selectedSociety = $request->query->get('society');
        
        

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
            'clientsFound' => $clientsFound,
            'societe' => $societe,
            'selectedSociety' => $selectedSociety,
            
        ]);
       
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $user = $this->getUser();
        $form = $this->createForm(ClientType::class, $client, [
            'user' => $user // Passe l'utilisateur actuel au formulaire
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ClientType::class, $client, [
            'user' => $user // Passe l'utilisateur actuel au formulaire
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/client-info/{id}', name: 'client_info', methods: ['GET'])]
    public function getClientInfo(ClientRepository $repository, int $id): JsonResponse
    {
        $client = $repository->find($id);
        
        if (!$client) {
            return new JsonResponse(['error' => 'Client non trouvé'], 404);
        }

        $data = [
            'type' => $client->getType(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'raison_sociale' => $client->getRaisonSociale(),
            'adresse' => $client->getAdresse(),
            'code_postal' => $client->getCodePostal(),
            'ville' => $client->getVille(),
            'email' => $client->getEmail(),
        ];

        return new JsonResponse($data);
    }
}