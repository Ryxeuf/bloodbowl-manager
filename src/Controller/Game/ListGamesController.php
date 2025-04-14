<?php

namespace App\Controller\Game;

use App\Service\GameServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game', name: 'app_game_index', methods: ['GET'])]
class ListGamesController extends AbstractController
{
    public function __construct(
        private GameServiceInterface $gameService
    ) {
    }

    public function __invoke(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $games = $this->gameService->getGamesForUser($user);

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }
} 