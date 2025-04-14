<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Team;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $games = $user->getGames();

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository): Response
    {
        $user = $this->getUser();
        $teams = $teamRepository->findBy(['user' => $user]);

        if ($request->isMethod('POST')) {
            $homeTeamId = $request->request->get('homeTeam');
            $awayTeamId = $request->request->get('awayTeam');

            $homeTeam = $teamRepository->find($homeTeamId);
            $awayTeam = $teamRepository->find($awayTeamId);

            if (!$homeTeam || !$awayTeam) {
                $this->addFlash('error', 'Équipes non trouvées');
                return $this->redirectToRoute('app_game_new');
            }

            $game = new Game();
            $game->setHomeTeam($homeTeam);
            $game->setAwayTeam($awayTeam);
            $game->setCurrentTurn(1);
            $game->setHomeScore(0);
            $game->setAwayScore(0);

            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_play', ['id' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/{id}/play', name: 'app_game_play', methods: ['GET', 'POST'])]
    #[IsGranted('GAME_ACCESS', subject: 'game')]
    public function play(Game $game, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $team = $request->request->get('team');

            switch ($action) {
                case 'score_home':
                    $game->setHomeScore($game->getHomeScore() + 1);
                    break;
                case 'score_away':
                    $game->setAwayScore($game->getAwayScore() + 1);
                    break;
                case 'next_turn':
                    $game->setCurrentTurn($game->getCurrentTurn() + 1);
                    break;
            }

            $entityManager->flush();
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
        ]);
    }
} 