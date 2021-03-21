<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IntPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Int PropType
 */
final class IntPropTypeTest extends UnitTestCase
{
    public function testGetType(): void
    {
        $subject = new IntPropType(false);
        Assert::assertSame('int', $subject->getType());
        $nullableSubject = new IntPropType(true);
        Assert::assertSame('?int', $nullableSubject->getType());
    }
}
