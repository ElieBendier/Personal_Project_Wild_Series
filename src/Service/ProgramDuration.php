<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $duration = [];
        $seasons = $program->getSeasons();
        foreach($seasons as $season){
            $episodes = $season->getEpisodes();
            foreach($episodes as $episode){
                $duration[] = $episode->getDuration();
            }
        }
        $totalDuration = array_sum($duration);
        $minutes = $totalDuration % 60;
        $hours = floor($totalDuration / 60) % 24;
        $days = floor($totalDuration / 60 / 24);
        return "Il te faudra " . $days . " jour(s), " . $hours . " heure(s) et " . $minutes . " minute(s) pour voir l'intégralité de cette série.";
    }
}