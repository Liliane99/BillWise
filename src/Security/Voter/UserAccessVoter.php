<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserAccessVoter extends Voter
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
    private const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {
            return false;
        }

        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
            case self::DELETE:
                return $this->canAccess($user, $authenticatedUser);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(User $user, User $authenticatedUser): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            if ($user->getCreatedBy() === $authenticatedUser) {
                return true;
            }

            foreach ($authenticatedUser->getSocieteId() as $societe) {
                if ($user->getSocieteId()->contains($societe)) {
                    return true;
                }
            }
        }

        return false;
    }
}

