<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player/{id}/info', name: 'app_player_info', methods: ['GET'])]
class GetPlayerInfoController extends AbstractController
{
    public function __construct(
        private PlayerRepository $playerRepository
    ) {
    }

    public function __invoke(Player $player): Response
    {
        throw new \Exception('test');
        $isPlayerFromUserTeam = false;
        $user = $this->getUser();
        
        if ($user) {
            $userTeams = $user->getTeams();
            $playerTeam = $player->getTeam();
            $isPlayerFromUserTeam = $userTeams->contains($playerTeam);
        }

        return $this->render('game/_player_info.html.twig', [
            'player' => $player,
            'isPlayerFromUserTeam' => $isPlayerFromUserTeam,
        ]);
    }
} 