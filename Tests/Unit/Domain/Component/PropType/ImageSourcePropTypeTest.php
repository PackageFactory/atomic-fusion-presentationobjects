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
     * @param ImageSourcePropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetSimpleName(ImageSourcePropType $subject, string $expectedName): void
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
     * @param ImageSourcePropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetUseStatement(ImageSourcePropType $subject, string $expectedName): void
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
     * @param ImageSourcePropType $subject
     * @param string $expectedType
     * @return void
     */
    public function testGetType(ImageSourcePropType $subject, string $expectedType): void
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
     * @param ImageSourcePropType $subject
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetStyleGuideValue(ImageSourcePropType $subject, string $expectedStyleGuideValue): void
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
     * @param ImageSourcePropType $subject
     * @param string $propName
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetDefinitionData(ImageSourcePropType $subject, string $propName, string $expectedStyleGuideValue): void
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
