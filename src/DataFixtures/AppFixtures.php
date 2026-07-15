<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $createdAt = \DateTimeImmutable::createFromMutable($faker->dateTimeThisYear());

            $review = new Review();
            $review->companyName = $faker->company();
            $review->rating = $faker->numberBetween(1, 5);
            $review->reviewText = $faker->paragraph();
            $review->authorEmail = $faker->safeEmail();
            $review->createdAt = $createdAt;
            $review->updatedAt = $createdAt;

            $manager->persist($review);
        }

        $manager->flush();
    }
}
