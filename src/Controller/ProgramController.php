<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/program', name: 'program_')]
Class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(#[MapEntity(mapping: ['programId' => 'id'])] Program $program, 
    #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season)
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')]
    public function showEpisode(#[MapEntity(mapping: ['programId' => 'id'])] Program $program, 
    #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
    #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}