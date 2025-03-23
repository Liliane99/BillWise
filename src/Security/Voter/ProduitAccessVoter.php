<?php

namespace App\Security\Voter;

use App\Entity\Produit;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ProduitAccessVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW = 'view';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Produit) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Produit $produit */
        $produit = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($produit, $user);
            case self::EDIT:
                return $this->canEdit($produit, $user);
            case self::DELETE:
                return $this->canDelete($produit, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Produit $produit, User $user): bool
    {
        return true;
    }

    private function canEdit(Produit $produit, User $user): bool
    {
        foreach ($user->getSocieteId() as $societe) {
            if ($produit->getSociety() === $societe) {
                return true;
            }
        }
        return false;
    }

    private function canDelete(Produit $produit, User $user): bool
    {
        return $this->canEdit($produit, $user);
    }
}
