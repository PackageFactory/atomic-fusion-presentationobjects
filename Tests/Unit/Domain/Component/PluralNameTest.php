<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PluralName;
use PHPUnit\Framework\Assert;

/**
 * Test for the PluralName translator
 */
final class PluralNameTest extends UnitTestCase
{
    public function testForName(): void
    {
        Assert::assertSame('Properties', PluralName::forName('Property'));
        Assert::assertSame('Cards', PluralName::forName('Card'));
    }

    public function testToName(): void
    {
        Assert::assertSame('Property', PluralName::toName('Properties'));
        Assert::assertSame('Card', PluralName::toName('Cards'));
        Assert::assertSame('Card', PluralName::toName('Card'));
    }
}
