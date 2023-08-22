<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumLabel;
use PHPUnit\Framework\Assert;

/**
 * Test cases for EnumLabel
 */
class EnumLabelTest extends UnitTestCase
{
    /**
     * @param string $enumName
     * @param EnumLabel $expectedLabel
     * @dataProvider enumProvider
     */
    public function testFromEnumName(string $enumName, EnumLabel $expectedLabel): void
    {
        Assert::assertEquals(
            $expectedLabel,
            EnumLabel::fromEnumName($enumName)
        );
    }

    /**
     * @return array<int, array<int, class-string<mixed>|EnumLabel>>
     */
    public function enumProvider(): array
    {
        return [
            [
                'Vendor\Site\Presentation\Component\Headline\HeadlineType',
                new EnumLabel('headlineType.', 'Component.Headline', 'Vendor.Site')
            ],
            [
                'Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum',
                new EnumLabel('myStringEnum.', 'Component.MyNewComponent', 'Vendor.Site')
            ]
        ];
    }
}
