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
        ['profile' => 'acteur1.jpg'],
        ['profile' => 'acteur2.jpg'],
        ['profile' => 'acteur3.jpg'],
        ['profile' => 'acteur4.jpg'],
        ['profile' => 'acteur5.jpg'],
        ['profile' => 'acteur6.jpg'],
        ['profile' => 'acteur7.jpg'],
        ['profile' => 'acteur8.jpg'],
        ['profile' => 'acteur9.jpg'],
        ['profile' => 'acteur10.jpg'],
    ];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
            foreach(self::ACTORS as $actorName){
            $actor = new Actor();
            $actor->setName($faker->name());
            $actor->setProfile($actorName['profile']);
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
