<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum;
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
     * @param EnumPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetSimpleName(EnumPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getSimpleName());
    }

    /**
     * @return array<mixed>
     */
    public function simpleNameProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringPseudoEnum::class, false),
                'MyStringPseudoEnum'
            ],
            [
                new EnumPropType(MyStringPseudoEnum::class, true),
                'MyStringPseudoEnum'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     * @param EnumPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetUseStatement(EnumPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getUseStatement());
    }

    /**
     * @return array<mixed>
     */
    public function useStatementProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringPseudoEnum::class, false),
                'use Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum;
'
            ],
            [
                new EnumPropType(MyStringPseudoEnum::class, true),
                'use Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     * @param EnumPropType $subject
     * @param string $expectedType
     * @return void
     */
    public function testGetType(EnumPropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

    /**
     * @return array<mixed>
     */
    public function typeProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringPseudoEnum::class, false),
                'MyStringPseudoEnum'
            ],
            [
                new EnumPropType(MyStringPseudoEnum::class, true),
                '?MyStringPseudoEnum'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     * @param EnumPropType $subject
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetStyleGuideValue(EnumPropType $subject, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getStyleGuideValue());
    }

    /**
     * @return array<mixed>
     */
    public function styleGuideValueProvider(): array
    {
        return [
            [
                new EnumPropType(MyStringPseudoEnum::class, false),
                '= \'myValue\''
            ],
            [
                new EnumPropType(MyStringPseudoEnum::class, true),
                '= \'myValue\''
            ]
        ];
    }
}
