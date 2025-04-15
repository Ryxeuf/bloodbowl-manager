<?php

namespace App\Service;

use App\Entity\PlayerGameState;
use App\Security\Voter\PlayerActionVoter;

/**
 * Service qui détermine quelles actions sont autorisées pour un joueur
 * 
 * Ce service implémente les règles métier qui définissent les actions autorisées 
 * pour un joueur en fonction de son état, de sa position, et d'autres facteurs.
 * 
 * Par défaut, seule l'action de déplacement (move) est autorisée, toutes les autres
 * sont refusées. Pour modifier ce comportement, il suffit de changer les méthodes
 * spécifiques (canBlock, canBlitz, etc.) dans cette classe.
 */
class PlayerActionAuthorizationService
{
    /**
     * Vérifie si un joueur peut effectuer une action spécifique
     */
    public function canPerformAction(PlayerGameState $playerState, string $actionType): bool
    {
        // Vérifie d'abord les conditions générales qui s'appliquent à toutes les actions
        if ($playerState->getState() !== PlayerGameState::STATE_AVAILABLE) {
            return false;
        }

        if ($playerState->hasPlayed()) {
            return false;
        }

        // Vérifie si le joueur a déjà une action en cours
        if ($playerState->getActionStatus() === PlayerGameState::ACTION_STATUS_IN_PROGRESS) {
            return false;
        }

        // Vérifie les règles spécifiques à chaque type d'action
        return match ($actionType) {
            PlayerActionVoter::ACTION_MOVE => $this->canMove($playerState),
            PlayerActionVoter::ACTION_BLOCK => $this->canBlock($playerState),
            PlayerActionVoter::ACTION_BLITZ => $this->canBlitz($playerState),
            PlayerActionVoter::ACTION_PASS => $this->canPass($playerState),
            PlayerActionVoter::ACTION_HANDOFF => $this->canHandoff($playerState),
            PlayerActionVoter::ACTION_FOUL => $this->canFoul($playerState),
            default => false,
        };
    }

    /**
     * Vérifie si un joueur peut se déplacer
     * 
     * Par défaut, autorise le déplacement pour tous les joueurs disponibles.
     */
    private function canMove(PlayerGameState $playerState): bool
    {
        return $playerState->getState() === PlayerGameState::STATE_AVAILABLE;
    }

    /**
     * Vérifie si un joueur peut effectuer un blocage
     * 
     * Pour autoriser le blocage, modifiez cette méthode pour ajouter des conditions, par exemple:
     * - Vérifier si le joueur a des adversaires adjacents
     * - Vérifier les statistiques du joueur (ST)
     */
    private function canBlock(PlayerGameState $playerState): bool
    {
        // Le joueur doit être disponible
        $state = $playerState->getState() === PlayerGameState::STATE_AVAILABLE;
        
        // Le joueur doit avoir au moins un adversaire adjacent
        $hasAdjacentOpponents = $this->hasAdjacentOpponents($playerState);
        
        return $state && $hasAdjacentOpponents;
    }

    /**
     * Vérifie si un joueur a des adversaires adjacents
     */
    private function hasAdjacentOpponents(PlayerGameState $playerState): bool
    {
        $currentX = $playerState->getX();
        $currentY = $playerState->getY();
        $playerTeam = $playerState->getPlayer()->getTeam();
        $game = $playerState->getGame();
        
        // Parcourir tous les états de joueurs dans la même partie
        foreach ($game->getPlayerStates() as $opponentState) {
            // Vérifier que c'est un joueur adverse et qu'il est disponible
            if ($opponentState->getPlayer()->getTeam() !== $playerTeam && 
                $opponentState->getState() === PlayerGameState::STATE_AVAILABLE) {
                
                $opponentX = $opponentState->getX();
                $opponentY = $opponentState->getY();
                
                // Vérifier si l'adversaire est adjacent (distance de 1 case)
                $dx = abs($currentX - $opponentX);
                $dy = abs($currentY - $opponentY);
                
                if ($dx <= 1 && $dy <= 1 && !($dx === 0 && $dy === 0)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Vérifie si un joueur peut effectuer un blitz
     * 
     * Pour autoriser le blitz, modifiez cette méthode pour ajouter des conditions, par exemple:
     * - Vérifier si un blitz a déjà été effectué ce tour
     * - Vérifier si le joueur a suffisamment de mouvement
     */
    private function canBlitz(PlayerGameState $playerState): bool
    {
        // Par défaut, interdit le blitz
        // Pour l'autoriser, remplacez 'return false' par vos conditions
        return false;
    }

    /**
     * Vérifie si un joueur peut effectuer une passe
     * 
     * Pour autoriser la passe, modifiez cette méthode pour ajouter des conditions, par exemple:
     * - Vérifier si le joueur a le ballon
     * - Vérifier si une passe a déjà été effectuée ce tour
     */
    private function canPass(PlayerGameState $playerState): bool
    {
        // Par défaut, interdit la passe
        // Pour l'autoriser, vous pourriez vérifier si le joueur a le ballon:
        return $playerState->hasBall();
    }

    /**
     * Vérifie si un joueur peut effectuer une transmission
     * 
     * Pour autoriser la transmission, modifiez cette méthode pour ajouter des conditions, par exemple:
     * - Vérifier si le joueur a le ballon
     * - Vérifier si une transmission a déjà été effectuée ce tour
     */
    private function canHandoff(PlayerGameState $playerState): bool
    {
        // Par défaut, interdit la transmission
        // Pour l'autoriser, vous pourriez vérifier si le joueur a le ballon:
        return $playerState->hasBall();
    }

    /**
     * Vérifie si un joueur peut effectuer une agression
     * 
     * Pour autoriser l'agression, modifiez cette méthode pour ajouter des conditions, par exemple:
     * - Vérifier s'il y a des joueurs adverses à terre à proximité
     * - Vérifier si le joueur a des compétences spécifiques
     */
    private function canFoul(PlayerGameState $playerState): bool
    {
        // Par défaut, interdit l'agression
        // Pour l'autoriser, remplacez 'return false' par vos conditions
        return false;
    }
} 