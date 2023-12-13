<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;
use DateTimeImmutable;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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

    #[Route('/show/{slug}', name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, SluggerInterface $slugger) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush(); 

            $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to($this->getParameter('mailer_from'))
                    ->subject('Une nouvelle série vient d\'être publiée !')
                    ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));
    
            $mailer->send($email);

            $this->addFlash('success', 'La série a bien été ajoutée ! Merci !');

            return $this->redirectToRoute('program_index');
        }
    
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}', name: 'season_show')]
    public function showSeason(#[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program, 
    #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season)
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{programSlug}/season/{seasonId}/episode/{episodeSlug}', name: 'episode_show')]
    public function showEpisode(#[MapEntity(mapping: ['programSlug' => 'slug'])] Program $program, 
    #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
    #[MapEntity(mapping: ['episodeSlug' => 'slug'])] Episode $episode,
    CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $date = new DateTimeImmutable('Europe/Paris');
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $comment->setCreatedAt($date);
            $entityManager->persist($comment);
            $entityManager->flush(); 
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->flush();

            $this->addFlash('success', 'La série a bien été modifiée ! Merci !');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }
}