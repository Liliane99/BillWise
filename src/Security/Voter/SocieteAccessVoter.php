<?php

namespace App\Security\Voter;

use App\Entity\soc\Societe;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class SocieteAccessVoter extends Voter
{
    private const EDIT = 'edit';
    private const VIEW = 'view';
    private const DELETE = 'delete';
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Societe;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Societe $societe */
        $societe = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($societe, $user);
            case self::EDIT:
            case self::DELETE:
                return $this->canEditOrDelete($societe, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Societe $societe, User $user): bool
    {
        return $this->security->isGranted('ROLE_ADMIN') && $user->getSocieteId()->contains($societe);
    }

    private function canEditOrDelete(Societe $societe, User $user): bool
    {
        return $this->security->isGranted('ROLE_ADMIN') && $user->getSocieteId()->contains($societe);
    }
}
