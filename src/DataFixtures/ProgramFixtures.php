<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        ['title' => 'Oz', 'synopsis' => 'Quartier expérimental de la prison créé par le visionnaire Tim McManus qui souhaite améliorer les conditions de vie des détenus. Mais dans cet univers clos et étouffant se recrée une société terrifiante où dominent la haine, la violence, la peur, la mort.', 'category' => 'Dramatique', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Game of Thrones', 'synopsis' => 'Après un été de dix années, un hiver rigoureux s\'abat sur le Royaume avec la promesse d\'un avenir des plus sombres. Pendant ce temps, complots et rivalités se jouent sur le continent pour s\'emparer du Trône de Fer, le symbole du pouvoir absolu.', 'category' => 'Fantastique', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Black Sails', 'synopsis' => '1715. Le légendaire Capitaine Flint et ses pirates règnent en maître sur New Providence. Lorsque la flotte britannique décide de reconquérir cette colonie abandonnée aux mains des mercenaires, une lutte violente s\'engage.', 'category' => 'Aventure', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Breakind Bad', 'synopsis' => 'Pour subvenir aux besoins de Skyler, sa femme enceinte, et de Walt Junior, son fils handicapé, il est obligé de travailler doublement. Son quotidien déjà morose devient carrément noir lorsqu\'il apprend qu\'il est atteint d\'un incurable cancer des poumons.', 'category' => 'Policier', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Better Call Saul', 'synopsis' => 'Six ans avant de croiser le chemin de Walter White, Saul Goodman, connu sous le nom de Jimmy McGill, est un avocat qui peine à joindre les deux bouts, à Albuquerque, au Nouveau-Mexique. Pour boucler ses fins de mois, il n\'aura d\'autres choix que se livrer à quelques petites escroqueries.', 'category' => 'Comédie', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Westworld', 'synopsis' => 'Westworld est un parc d\'attractions futuriste dans lequel des robots recréent un monde western et rejouent les mêmes scènes à l\'infini. Pourtant, un bug va enrayer la machine (ou plutôt les machines) et faire prendre conscience à certains d\'entre eux le monde dans lequel ils vivent.', 'category' => 'Science-Fiction', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Mr. Robot', 'synopsis' => 'Elliot est un jeune programmeur antisocial qui souffre d\'un trouble du comportement. Il est recruté par un anarchiste mystérieux, qui se fait appeler Mr. Robot.', 'category' => 'Dramatique', 'country' => 'Etats-Unis', 'year' => 2000],
        ['title' => 'Peaky Blinders', 'synopsis' => 'En 1919, à Birmingham, soldats, révolutionnaires, politiques et criminels combattent pour se faire une place dans le paysage industriel de l\'après-guerre. Le Parlement s\'attend à une violente révolte, et Winston Churchill mobilise des forces spéciales pour contenir les menaces.', 'category' => 'Historique', 'country' => 'Angleterre', 'year' => 2000],
        ['title' => 'DrHouse', 'synopsis' => 'Le docteur House est un célèbre diagnosticien travaillant à l\'hôpital de Princeton. Secondé par une équipe de trois jeunes gens (des spécialistes dans le genre), il tente de découvrir les maladies qui rongent ses patients. Son credo : concevoir les symptômes comme des indices le menant petit à petit au coupable.', 'category' => 'Comédie', 'country' => 'Etats-Unis', 'year' => 2000],
    ];
    public function load(ObjectManager $manager)
    {
        $i=0;
        foreach (self::PROGRAMS as $programName) {
            $i++;
            $program = new Program();
            $program->setTitle($programName['title']);
            $program->setSynopsis($programName['synopsis']);
            $program->setCountry($programName['country']);
            $program->setYear($programName['year']);
            $program->setCategory($this->getReference('category_'.$programName['category']));
            $this->addReference('program_'.$i.'', $program);
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }


}
