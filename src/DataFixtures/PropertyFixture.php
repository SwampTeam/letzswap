<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use PhpParser\Builder\Property;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('en_GB');
        for ($i = 0; $i < 100; $i++){
            $property = new Property();
            $property
                ->setType($faker->words(2, true))
                ->setDescription($faker->sentences(3, true))
                ->setPicture($faker->image('image',250,200));
            $manager->persist($property);
        }

        $manager->flush();
    }
}
