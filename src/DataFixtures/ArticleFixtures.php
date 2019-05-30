<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 29/05/2019
 * Time: 17:37
 */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 49; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->sentence));
            $article->setContent($faker->text);

            $manager->persist($article);
            $article->setCategory($this->getReference('category_' . rand(0, 5)));
            $manager->flush();

        }

    }


}