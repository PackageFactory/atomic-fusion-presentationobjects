<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ComponentPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Component PropType
 */
final class ComponentPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     * @param ComponentPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetSimpleName(ComponentPropType $subject, string $expectedName): void
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
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), true),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), true),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true),
                'MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     * @param ComponentPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetUseStatement(ComponentPropType $subject, string $expectedName): void
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
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), false),
                'use Vendor\Site\Presentation\Component\MyComponent\MyComponent;
'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), true),
                'use Vendor\Site\Presentation\Component\MyComponent\MyComponent;
'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), false),
                'use Vendor\Site\Presentation\CustomType\MyComponent\MyComponent;
'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), true),
                'use Vendor\Site\Presentation\CustomType\MyComponent\MyComponent;
'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false),
                'use Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponent;
'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true),
                'use Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponent;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     * @param ComponentPropType $subject
     * @param string $expectedType
     * @return void
     */
    public function testGetType(ComponentPropType $subject, string $expectedType): void
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
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), true),
                '?MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), true),
                '?MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false),
                'MyComponent'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true),
                '?MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     * @param ComponentPropType $subject
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetStyleGuideValue(ComponentPropType $subject, string $expectedStyleGuideValue): void
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
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'AnotherComponent'), false),
                '{
            number = 4711
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), false),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), true),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), false),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), true),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true),
                '{
            text = \'Text\'
            other {
                number = 4711
            }
        }'
            ]
        ];
    }

    /**
     * @dataProvider definitionDataProvider
     * @param ComponentPropType $subject
     * @param string $propName
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetDefinitionData(ComponentPropType $subject, string $propName, string $expectedStyleGuideValue): void
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
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), false),
                'myProperty',
                '
                <Vendor.Site:Component.MyComponent presentationObject={presentationObject.myProperty} />
            '
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'), true),
                'myProperty',
                '
                <Vendor.Site:Component.MyComponent presentationObject={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), false),
                'myProperty',
                '
                <Vendor.Site:CustomType.MyComponent presentationObject={presentationObject.myProperty} />
            '
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'), true),
                'myProperty',
                '
                <Vendor.Site:CustomType.MyComponent presentationObject={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false),
                'myProperty',
                '
                <Vendor.Site:Custom.Type.MyComponent presentationObject={presentationObject.myProperty} />
            '
            ],
            [
                new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true),
                'myProperty',
                '
                <Vendor.Site:Custom.Type.MyComponent presentationObject={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ]
        ];
    }
}
