<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        ['profil' => '/build/images/acteur1.1080ade4.jpg'],
        ['profil' => '/build/images/acteur2.69f5612c.jpg'],
        ['profil' => '/build/images/acteur3.f35d2a06.jpg'],
        ['profil' => '/build/images/acteur4.43cf455a.jpg'],
        ['profil' => '/build/images/acteur5.ef1a4127.jpg'],
        ['profil' => '/build/images/acteur6.7b00b873.jpg'],
        ['profil' => '/build/images/acteur7.ac96ad7f.jpg'],
        ['profil' => '/build/images/acteur8.8855746e.jpg'],
        ['profil' => '/build/images/acteur9.2395678f.jpg'],
        ['profil' => '/build/images/acteur10.04e9817b.jpg'],
    ];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
            foreach(self::ACTORS as $actorName){
            $actor = new Actor();
            $actor->setName($faker->name());
            $actor->setProfil($actorName['profil']);
            $program1 = rand(1, 9);
            $program2 = rand(1, 9);
            if ($program1 === $program2) {
                $program2 = rand(1, 9);
            }
            $program3 = rand(1, 9);
            if ($program1 === $program3 || $program2 === $program3) {
                $program3 = rand(1, 9);
            }
            $actor->addProgram($this->getReference('program_'.$program1));
            $actor->addProgram($this->getReference('program_'.$program2));
            $actor->addProgram($this->getReference('program_'.$program3));
            $manager->persist($actor);

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
