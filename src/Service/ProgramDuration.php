<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $program->getSeasons();
        var_dump($program);
        die();
        return "Coming soon";
    }
}