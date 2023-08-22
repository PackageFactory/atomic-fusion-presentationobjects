<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\StringPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the String PropType
 */
final class StringPropTypeTest extends UnitTestCase
{
    public function testGetType(): void
    {
        $subject = new StringPropType(false);
        Assert::assertSame('string', $subject->getType());
        $nullableSubject = new StringPropType(true);
        Assert::assertSame('?string', $nullableSubject->getType());
    }
}
