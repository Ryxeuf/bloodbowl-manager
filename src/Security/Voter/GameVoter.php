<?php

namespace App\Security\Voter;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GameVoter extends Voter
{
    public const GAME_ACCESS = 'GAME_ACCESS';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::GAME_ACCESS && $subject instanceof Game;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Game $game */
        $game = $subject;

        return $game->getHomeTeam()->getUser() === $user || $game->getAwayTeam()->getUser() === $user;
    }
} 