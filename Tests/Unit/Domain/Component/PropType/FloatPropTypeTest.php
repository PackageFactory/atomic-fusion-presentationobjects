<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\FloatPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Float PropType
 */
final class FloatPropTypeTest extends UnitTestCase
{
    public function testGetType(): void
    {
        $subject = new FloatPropType(false);
        Assert::assertSame('float', $subject->getType());
        $nullableSubject = new FloatPropType(true);
        Assert::assertSame('?float', $nullableSubject->getType());
    }
}
