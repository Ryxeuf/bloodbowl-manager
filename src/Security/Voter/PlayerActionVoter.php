<?php

namespace App\Security\Voter;

use App\Entity\PlayerGameState;
use App\Service\PlayerActionAuthorizationService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerActionVoter extends Voter
{
    // Définition des attributs pour les actions
    public const ACTION_MOVE = 'action_move';
    public const ACTION_BLOCK = 'action_block';
    public const ACTION_BLITZ = 'action_blitz';
    public const ACTION_PASS = 'action_pass';
    public const ACTION_HANDOFF = 'action_handoff';
    public const ACTION_FOUL = 'action_foul';

    public function __construct(
        private PlayerActionAuthorizationService $authorizationService
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Vérifier que l'attribut est un des attributs d'action définis
        $attributes = [
            self::ACTION_MOVE,
            self::ACTION_BLOCK, 
            self::ACTION_BLITZ,
            self::ACTION_PASS,
            self::ACTION_HANDOFF,
            self::ACTION_FOUL
        ];

        return in_array($attribute, $attributes) && $subject instanceof PlayerGameState;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var PlayerGameState $playerState */
        $playerState = $subject;

        // Utiliser le service d'autorisation pour déterminer si l'action est permise
        return $this->authorizationService->canPerformAction($playerState, $attribute);
    }
} 