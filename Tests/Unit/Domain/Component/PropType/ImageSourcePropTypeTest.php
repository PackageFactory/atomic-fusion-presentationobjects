<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

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

    public function simpleNameProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'ImageSourceHelperInterface'
            ],
            [
                new ImageSourcePropType(true),
                'ImageSourceHelperInterface'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     */
    public function testGetUseStatement(ImageSourcePropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getUseStatement());
    }

    public function useStatementProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
'
            ],
            [
                new ImageSourcePropType(true),
                'use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(ImageSourcePropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

    public function typeProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'ImageSourceHelperInterface'
            ],
            [
                new ImageSourcePropType(true),
                '?ImageSourceHelperInterface'
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

    public function styleGuideValueProvider(): array
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
    public function testGetDefinitionData(ImageSourcePropType $subject, string $propName, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getDefinitionData($propName));
    }

    public function definitionDataProvider(): array
    {
        return [
            [
                new ImageSourcePropType(false),
                'myProperty',
                '
            <Sitegeist.Lazybones:Image imageSource={presentationObject.myProperty} />
        '
            ],
            [
                new ImageSourcePropType(true),
                'myProperty',
                '
            <Sitegeist.Lazybones:Image imageSource={presentationObject.myProperty} @if.isToBeRendered={presentationObject.myProperty} />
        '
            ]
        ];
    }
}
