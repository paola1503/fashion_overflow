<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $question=new Question();
        $question->setName('White dress for a wedding')
            ->setSlug('white-dress-for-a-wedding-'.rand(0,1000))
            ->setQuestion(<<<EOF
Hi everyone! I've been invited to a wedding and I've found a beautiful dress for the occasion, but I don't know if it's "too white". Its main color is indeed white, but it has golden dots all over it. Do you think I can wear it, or is the bride going to be angry at me?
EOF
            );
        if (rand(1,10)>2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
        }
        $question->setVotes(rand(-20,50));
        $manager->persist($question);

        $manager->flush();
    }
}
