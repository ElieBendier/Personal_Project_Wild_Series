<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for($i = 1; $i <11; $i++){
            for($t = 1; $t < 6; $t++){
                for($p = 1; $p < 10; $p++){
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence(3));
                    $episode->setNumber($i);
                    $episode->setSynopsis($faker->paragraphs(4, true));
                    $episode->setSeason($this->getReference('season'.$t.'_program'.$p));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
