<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\EnumPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Enum PropType
 */
final class EnumPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     */
    public function testGetSimpleName(EnumPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getSimpleName());
    }

    public function simpleNameProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringEnum::class, false),
                'MyStringEnum'
            ],
            [
                new EnumPropType(MyStringEnum::class, true),
                'MyStringEnum'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     */
    public function testGetUseStatement(EnumPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getUseStatement());
    }

    public function useStatementProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringEnum::class, false),
                'use Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum;
'
            ],
            [
                new EnumPropType(MyStringEnum::class, true),
                'use Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(EnumPropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

    public function typeProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringEnum::class, false),
                'MyStringEnum'
            ],
            [
                new EnumPropType(MyStringEnum::class, true),
                '?MyStringEnum'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     */
    public function testGetStyleGuideValue(EnumPropType $subject, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getStyleGuideValue());
    }

    public function styleGuideValueProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringEnum::class, false),
                '= \'myValue\''
            ],
            [
                new EnumPropType(MyStringEnum::class, true),
                '= \'myValue\''
            ]
        ];
    }
}
