<?php

namespace App\Tests\Controller\Game;

use App\Controller\Game\GetAvailableMovesController;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\PlayerGameState;
use App\Repository\PlayerGameStateRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetAvailableMovesControllerTest extends TestCase
{
    private GetAvailableMovesController $controller;
    private PlayerGameStateRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PlayerGameStateRepository::class);
        $this->controller = new GetAvailableMovesController($this->repository);
    }

    public function testPlayerCannotMoveThroughOccupiedSquare()
    {
        // Créer un joueur avec M=6
        $player = new Player();
        $player->setM(6);

        // Créer un état de jeu pour le joueur
        $playerState = new PlayerGameState();
        $playerState->setPlayer($player);
        $playerState->setX(5);
        $playerState->setY(5);
        $playerState->setHasPlayed(false);

        // Créer un autre joueur qui bloque le chemin
        $blockingPlayerState = new PlayerGameState();
        $blockingPlayerState->setX(6);
        $blockingPlayerState->setY(5);

        // Configurer le repository pour retourner les états
        $this->repository->method('findOneBy')->willReturn($playerState);
        $this->repository->method('findBy')->willReturn([$playerState, $blockingPlayerState]);

        // Appeler le contrôleur
        $response = $this->controller->__invoke(new Game(), $player);

        // Vérifier que la case (7,5) n'est pas accessible car le chemin est bloqué
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertNotContains(['x' => 7, 'y' => 5], $data['availableMoves']);
    }

    public function testPlayerCannotMoveDiagonallyThroughOccupiedSquare()
    {
        // Créer un joueur avec M=6
        $player = new Player();
        $player->setM(6);

        // Créer un état de jeu pour le joueur
        $playerState = new PlayerGameState();
        $playerState->setPlayer($player);
        $playerState->setX(5);
        $playerState->setY(5);
        $playerState->setHasPlayed(false);

        // Créer un autre joueur qui bloque le chemin diagonal
        $blockingPlayerState = new PlayerGameState();
        $blockingPlayerState->setX(6);
        $blockingPlayerState->setY(5);

        // Configurer le repository pour retourner les états
        $this->repository->method('findOneBy')->willReturn($playerState);
        $this->repository->method('findBy')->willReturn([$playerState, $blockingPlayerState]);

        // Appeler le contrôleur
        $response = $this->controller->__invoke(new Game(), $player);

        // Vérifier que la case (6,6) n'est pas accessible car le chemin diagonal est bloqué
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertNotContains(['x' => 6, 'y' => 6], $data['availableMoves']);
    }

    public function testPlayerCannotMoveWhenHasPlayed()
    {
        // Créer un joueur avec M=6
        $player = new Player();
        $player->setM(6);

        // Créer un état de jeu pour le joueur qui a déjà joué
        $playerState = new PlayerGameState();
        $playerState->setPlayer($player);
        $playerState->setX(5);
        $playerState->setY(5);
        $playerState->setHasPlayed(true);

        // Configurer le repository pour retourner l'état
        $this->repository->method('findOneBy')->willReturn($playerState);
        $this->repository->method('findBy')->willReturn([$playerState]);

        // Appeler le contrôleur
        $response = $this->controller->__invoke(new Game(), $player);

        // Vérifier qu'aucun mouvement n'est disponible
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertEmpty($data['availableMoves']);
    }

    public function testPlayerCanMoveToAdjacentEmptySquare()
    {
        // Créer un joueur avec M=6
        $player = new Player();
        $player->setM(6);

        // Créer un état de jeu pour le joueur
        $playerState = new PlayerGameState();
        $playerState->setPlayer($player);
        $playerState->setX(5);
        $playerState->setY(5);
        $playerState->setHasPlayed(false);

        // Configurer le repository pour retourner l'état
        $this->repository->method('findOneBy')->willReturn($playerState);
        $this->repository->method('findBy')->willReturn([$playerState]);

        // Appeler le contrôleur
        $response = $this->controller->__invoke(new Game(), $player);

        // Vérifier que la case adjacente (6,5) est accessible
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertContains(['x' => 6, 'y' => 5], $data['availableMoves']);
    }
} 