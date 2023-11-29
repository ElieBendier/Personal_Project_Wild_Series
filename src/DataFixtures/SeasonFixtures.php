<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for($i = 1; $i < 6; $i++){
            for($t = 1; $t < 10; $t++){
                $season = new Season();
                $season->setNumber($i);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                $season->setProgram($this->getReference('program_'.$t));
                $this->addReference('season'.$i.'_program'.$t, $season);
                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}
