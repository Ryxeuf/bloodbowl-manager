<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\FactionRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/teams', name: 'app_teams')]
    public function index(TeamRepository $teamRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $teams = $teamRepository->findBy(['user' => $user]);

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route('/teams/{id}', name: 'app_team_show')]
    public function show(Team $team): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($team->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/teams/new', name: 'app_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactionRepository $factionRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $faction = $factionRepository->find($request->request->get('faction'));
            if (!$faction) {
                $this->addFlash('error', 'Faction invalide');
                return $this->redirectToRoute('app_team_new');
            }

            $team = new Team();
            $team->setName($request->request->get('name'));
            $team->setFaction($faction);
            $team->setUser($user);

            $entityManager->persist($team);
            $entityManager->flush();

            $this->addFlash('success', 'Équipe créée avec succès');
            return $this->redirectToRoute('app_teams');
        }

        $factions = $factionRepository->findAll();

        return $this->render('team/new.html.twig', [
            'factions' => $factions,
        ]);
    }

    #[Route('/team/{id}/edit', name: 'app_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($team->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(TeamType::class, $team, [
            'faction_id' => $team->getFaction()->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_team_show', ['id' => $team->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }
} 