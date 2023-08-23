<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\UriPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Uri PropType
 */
final class UriPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     */
    public function testGetSimpleName(UriPropType $subject, string $expectedName): void
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
                new UriPropType(false),
                'UriInterface'
            ],
            [
                new UriPropType(true),
                'UriInterface'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     */
    public function testGetUseStatement(UriPropType $subject, string $expectedName): void
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
                new UriPropType(false),
                'use Psr\Http\Message\UriInterface;
'
            ],
            [
                new UriPropType(true),
                'use Psr\Http\Message\UriInterface;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(UriPropType $subject, string $expectedType): void
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
                new UriPropType(false),
                'UriInterface'
            ],
            [
                new UriPropType(true),
                '?UriInterface'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     */
    public function testGetStyleGuideValue(UriPropType $subject, string $expectedStyleGuideValue): void
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
                new UriPropType(false),
                '= \'https://www.neos.io\''
            ],
            [
                new UriPropType(true),
                '= \'https://www.neos.io\''
            ]
        ];
    }


    /**
     * @dataProvider definitionDataProvider
     */
    public function testGetDefinitionData(UriPropType $subject, string $propName, string $expectedDefinitionData): void
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
                new UriPropType(false),
                'myProperty',
                '{presentationObject.myProperty}'
            ],
            [
                new UriPropType(true),
                'myProperty',
                '{presentationObject.myProperty}'
            ]
        ];
    }
}
