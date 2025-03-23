<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Repository\UserRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository,SocieteRepository $societeRepository, Request $request): Response
    {
        $currentUser = $this->getUser(); 
        $currentPage = $request->query->getInt('page', 1); 
        $limit = 8; 
        $search = $request->query->get('search'); 

        $queryBuilder = $userRepository->createQueryBuilder('u');

        if ($currentUser instanceof User && in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            $queryBuilder = $queryBuilder
            ->leftJoin('u.societe_id', 's') 
            ->leftJoin('s.users', 'su')
            ->where('u.createdBy = :currentUserId')
            ->orWhere(':currentUserId MEMBER OF s.users') 
            ->setParameter('currentUserId', $currentUser->getId());
        }

        $societeId = $request->query->get('societe_id');

        $users = $societeId ? $userRepository->findBySocieteId($societeId) : $userRepository->findAllAccessibleByUser($currentUser);
        $roleCounts = $this->calculateRoleCounts($users);
        $societes = $societeRepository->findAllAccessibleSocietes($currentUser);

        if (!empty($search)) {
            $queryBuilder->andWhere('u.email LIKE :search')
                        ->setParameter('search', '%' . $search . '%');
        }

        $filteredUsers = $queryBuilder->getQuery()->getResult();
        $totalUsers = count($filteredUsers);

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginator->getQuery()
            ->setFirstResult($limit * ($currentPage - 1)) 
            ->setMaxResults($limit); 

        $maxPages = ceil($totalUsers / $limit); 
        $usersFound = count($paginator) > 0;

        $usersAndSocieties = [];
        foreach ($paginator as $user) {
            $createdBy = $user->getCreatedBy(); 
            $createdByName = $createdBy ? $createdBy->getName() . ' ' . $createdBy->getSurname() : 'N/A';
        
            $updatedBy = $user->getUpdatedBy();
            $updatedByName = $updatedBy ? $updatedBy->getName() . ' ' . $updatedBy->getSurname() : 'N/A';
        
            $usersAndSocieties[] = [
                'user' => $user,
                'societies' => $user->getSocieteId()->toArray(),
                'createdByName' => $createdByName,
                'updatedByName' => $updatedByName,
            ];
        }

        return $this->render('user/index.html.twig', [
            'usersAndSocieties' => $usersAndSocieties,
            'maxPages' => $maxPages,
            'currentPage' => $currentPage,
            'search' => $search,
            'usersFound' => $usersFound,
            'roleCounts' => $roleCounts,
            'societes' => $societes,
            'selectedSocieteId' => $societeId
        ]);
    } 

    private function calculateRoleCounts($users): array
    {
        $roleCounts = ['ROLE_ADMIN' => 0, 'ROLE_COMPTABLE' => 0, 'ROLE_USER' => 0];
        foreach ($users as $user) {
            foreach ($user->getRoles() as $role) {
                if (isset($roleCounts[$role])) {
                    $roleCounts[$role]++;
                }
            }
        }
        return $roleCounts;
    }
    
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $admin = $this->getUser();
        $user = new User();
        $adminSocietes = $admin->getSocieteId()->toArray();
        $form = $this->createForm(UserType::class, $user, [
            'admin' => $admin,
            'admin_societes' => $adminSocietes 
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('password')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                );
                $user->setPassword($hashedPassword);
            }

            $selectedSocietes = $user->getSocieteId();
            foreach ($selectedSocietes as $societe) {
                $user->addSocieteId($societe);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('view', $user)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        $societes = $user->getSocieteId()->toArray();

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'societes' => $societes,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('edit', $user)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à cette page pour modifier cet utilisateur.');
        }

        $admin = $this->getUser();
        $adminSocietes = $admin->getSocieteId()->toArray();

        $form = $this->createForm(UserType::class, $user, [
            'admin_societes' => $adminSocietes, 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                );
                $user->setPassword($hashedPassword);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (!$authorizationChecker->isGranted('view', $user)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer cet utilisateur.');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getSlug(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/profile', name: 'app_user_profile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $societes = $user->getSocieteId()->toArray();

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'societes' => $societes,
        ]);
    }


}
