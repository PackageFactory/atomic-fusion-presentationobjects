<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the PackageKey value object
 */
final class PackageKeyTest extends UnitTestCase
{
    public function testToFusionNamespace(): void
    {
        $packageKey = new PackageKey('Vendor.Site');
        Assert::assertSame('Vendor.Site:', $packageKey->toFusionNamespace());
    }

    public function testToPhpNamespace(): void
    {
        $packageKey = new PackageKey('Vendor.Site');
        Assert::assertSame('Vendor\Site', $packageKey->toPhpNamespace());
    }

    public function testGetSimpleName(): void
    {
        $packageKey = new PackageKey('Vendor.Site');
        Assert::assertSame('Site', $packageKey->getSimpleName());
    }
}
