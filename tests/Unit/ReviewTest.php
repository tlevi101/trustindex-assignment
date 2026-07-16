<?php

namespace App\Tests\Unit;

use App\Entity\Review;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    public function testCreationSetsBothTimestampsToTheSameInstant(): void
    {
        $review = new Review();

        $review->setTimestampsOnCreate();

        self::assertEqualsWithDelta(time(), $review->createdAt->getTimestamp(), 2);
        self::assertSame($review->createdAt, $review->updatedAt);
    }

    public function testRefreshUpdatedAtLeavesCreatedAtUntouched(): void
    {
        $review = new Review();
        $review->setTimestampsOnCreate();
        $createdAt = $review->createdAt;

        $review->refreshUpdatedAt();

        self::assertSame($createdAt, $review->createdAt);
        self::assertNotSame($createdAt, $review->updatedAt);
        self::assertGreaterThanOrEqual($createdAt, $review->updatedAt);
    }
}
