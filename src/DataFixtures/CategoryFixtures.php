<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 29/05/2019
 * Time: 16:47
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'PHP',
        'Java',
        'Javascript',
        'Ruby',
        'DevOps',
        'Python'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $value) {
            $category = new Category();
            $category->setName($value);
            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }

}