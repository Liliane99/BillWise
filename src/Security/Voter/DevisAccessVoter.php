<?php

namespace App\Security\Voter;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class DevisAccessVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = 'view';
    const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE]) && $subject instanceof Devis;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Devis $devis */
        $devis = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($devis, $user);
            case self::EDIT:
            case self::DELETE:
                return $this->canEditOrDelete($devis, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Devis $devis, User $user): bool
    {
        return true;
    }

    private function canEditOrDelete(Devis $devis, User $user): bool
    {
        foreach ($user->getSocieteId() as $societe) {
            if ($devis->getSociety() === $societe) {
                return true;
            }
        }
        return false;
    }
}
