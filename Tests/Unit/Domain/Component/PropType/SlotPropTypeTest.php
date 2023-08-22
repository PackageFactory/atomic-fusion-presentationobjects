<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\SlotPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Slot PropType
 */
final class SlotPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     */
    public function testGetSimpleName(SlotPropType $subject, string $expectedName): void
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
                new SlotPropType(false),
                'slot'
            ],
            [
                new SlotPropType(true),
                'slot'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     */
    public function testGetUseStatement(SlotPropType $subject, string $expectedName): void
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
                new SlotPropType(false),
                'use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
'
            ],
            [
                new SlotPropType(true),
                'use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(SlotPropType $subject, string $expectedType): void
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
                new SlotPropType(false),
                'SlotInterface'
            ],
            [
                new SlotPropType(true),
                '?SlotInterface'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     */
    public function testGetStyleGuideValue(SlotPropType $subject, string $expectedStyleGuideValue): void
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
                new SlotPropType(false),
                '= \'- Add Slot Content -\''
            ],
            [
                new SlotPropType(true),
                '= \'- Add Slot Content -\''
            ]
        ];
    }


    /**
     * @dataProvider definitionDataProvider
     */
    public function testGetDefinitionData(SlotPropType $subject, string $propName, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getDefinitionData($propName));
    }

    /**
     * @return array<mixed>
     */
    public function definitionDataProvider(): array
    {
        return [
            [
                new SlotPropType(false),
                'myProperty',
                '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} />
            '
            ],
            [
                new SlotPropType(true),
                'myProperty',
                '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ]
        ];
    }
}
