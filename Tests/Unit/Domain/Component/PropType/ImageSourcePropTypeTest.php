<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ImageSourcePropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the ImageSource PropType
 */
final class ImageSourcePropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     */
    public function testGetSimpleName(ImageSourcePropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getSimpleName());
    }

    /**
     * @return array<mixed>
     */
    public static function simpleNameProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'ImageSourceInterface'
            ],
            [
                new ImageSourcePropType(true),
                'ImageSourceInterface'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     */
    public static function testGetUseStatement(ImageSourcePropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getUseStatement());
    }

    /**
     * @return array<mixed>
     */
    public static function useStatementProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;
'
            ],
            [
                new ImageSourcePropType(true),
                'use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public static function testGetType(ImageSourcePropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

    /**
     * @return array<mixed>
     */
    public static function typeProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'ImageSourceInterface'
            ],
            [
                new ImageSourcePropType(true),
                '?ImageSourceInterface'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     */
    public function testGetStyleGuideValue(ImageSourcePropType $subject, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getStyleGuideValue());
    }

    /**
     * @return array<mixed>
     */
    public static function styleGuideValueProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                '= Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }'
            ],
            [
                new ImageSourcePropType(true),
                '= Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }'
            ]
        ];
    }


    /**
     * @dataProvider definitionDataProvider
     */
    public function testGetDefinitionData(ImageSourcePropType $subject, string $propName, string $expectedDefinitionData): void
    {
        Assert::assertSame($expectedDefinitionData, $subject->getDefinitionData($propName));
    }

    /**
     * @return array<mixed>
     */
    public static function definitionDataProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'myProperty',
                '
                <Sitegeist.Kaleidoscope:Image imageSource={presentationObject.myProperty} />
            '
            ],
            [
                new ImageSourcePropType(true),
                'myProperty',
                '
                <Sitegeist.Kaleidoscope:Image imageSource={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ]
        ];
    }
}
