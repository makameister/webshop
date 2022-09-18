<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductAndCategory extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('us_US');

        $categories = [];

        $categoryList = [
            'jouet enfant',
            'electronnique',
            'musique',
            'informatique',
            'multimédia',
            'film',
            'téléphonne',
        ];

        foreach ($categoryList as $c) {
            $category = new Category();
            $category->setDescription($faker->sentence(3, true))
                ->setName($c);
            $categories[] = $category;
            $manager->persist($category);
            $manager->flush();
        }

        /*
        for ($i = 0; $i <= 10; $i++) {
            $category = new Category();
            $category->setDescription($faker->paragraph(2, true))
                ->setName($faker->sentence(3, true));
            $categories[] = $category;
            $manager->persist($category);
            $manager->flush();
        }
        */

        for ($i = 0; $i <= 100; $i++) {
            $product = new Product();
            $product->setName($faker->company)
                ->setDescription($faker->paragraph(2, true))
                ->setBrand($faker->company . $faker->companySuffix)
                ->setCategory($categories[rand(0,6)])
                ->setCreatedAt(new \DateTime($faker->date()))
                ->setImage($faker->imageUrl('286', '180'))
                ->setPrice($faker->randomFloat(2, 7, 1000));
            $manager->persist($product);
            $manager->flush();
        }
    }
}
