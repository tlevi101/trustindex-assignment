<?php

namespace App\Tests\Functional;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompanyStatsRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ReviewRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->repository = $container->get(ReviewRepository::class);

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        try {
            $schemaTool->dropSchema($metadata);
        } catch (\Throwable) {
        }
        $schemaTool->createSchema($metadata);
    }

    public function testGetCompanyStatsAveragesAndOrdersDescending(): void
    {
        $this->persistReview('Alpha', 4);
        $this->persistReview('Alpha', 2);
        $this->persistReview('Beta', 5);
        $this->persistReview('Beta', 5);
        $this->persistReview('Gamma', 1);
        $this->em->flush();

        $stats = $this->repository->getCompanyStats();

        self::assertSame(['Beta', 'Alpha', 'Gamma'], array_column($stats, 'companyName'));

        $byName = array_column($stats, null, 'companyName');
        self::assertSame(2, (int) $byName['Alpha']['reviewCount']);
        self::assertEqualsWithDelta(3.0, (float) $byName['Alpha']['averageRating'], 0.0001);
        self::assertEqualsWithDelta(5.0, (float) $byName['Beta']['averageRating'], 0.0001);
        self::assertEqualsWithDelta(1.0, (float) $byName['Gamma']['averageRating'], 0.0001);
    }

    private function persistReview(string $company, int $rating): void
    {
        $review = new Review();
        $review->companyName = $company;
        $review->rating = $rating;
        $review->reviewText = 'Test review text';
        $review->authorEmail = 'tester@example.com';
        $this->em->persist($review);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
    }
}
