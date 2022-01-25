<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagsFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        TagsFactory::createMany(5);
        ArticleFactory::createMany(10);
        QuestionFactory::createMany(10);
    }
}
