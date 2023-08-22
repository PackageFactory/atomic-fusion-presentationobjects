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
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ComponentArrayPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the ComponentArray PropType
 */
final class ComponentArrayPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider simpleNameProvider
     * @param ComponentArrayPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetSimpleName(ComponentArrayPropType $subject, string $expectedName): void
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
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                'MyComponents'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent')),
                'MyComponents'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')),
                'MyComponents'
            ]
        ];
    }

    /**
     * @dataProvider useStatementProvider
     * @param ComponentArrayPropType $subject
     * @param string $expectedName
     * @return void
     */
    public function testGetUseStatement(ComponentArrayPropType $subject, string $expectedName): void
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
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                'use Vendor\Site\Presentation\Component\MyComponent\MyComponents;
'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent')),
                'use Vendor\Site\Presentation\CustomType\MyComponent\MyComponents;
'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')),
                'use Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponents;
'
            ]
        ];
    }

    /**
     * @dataProvider typeProvider
     * @param ComponentArrayPropType $subject
     * @param string $expectedType
     * @return void
     */
    public function testGetType(ComponentArrayPropType $subject, string $expectedType): void
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
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                'MyComponents'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent')),
                'MyComponents'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')),
                'MyComponents'
            ]
        ];
    }

    /**
     * @dataProvider styleGuideValueProvider
     * @param ComponentArrayPropType $subject
     * @param string $expectedStyleGuideValue
     * @return void
     */
    public function testGetStyleGuideValue(ComponentArrayPropType $subject, string $expectedStyleGuideValue): void
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
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                '{
            0 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
            1 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
        }'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent')),
                '{
            0 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
            1 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
        }'
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')),
                '{
            0 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
            1 {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
        }'
            ]
        ];
    }

    /**
     * @dataProvider definitionDataProvider
     * @param ComponentArrayPropType $subject
     * @param string $propName
     * @param string $expectedDefinitionData
     * @return void
     */
    public function testGetDefinitionData(ComponentArrayPropType $subject, string $propName, string $expectedDefinitionData): void
    {
        Assert::assertSame($expectedDefinitionData, $subject->getDefinitionData($propName));
    }

    /**
     * @return array<mixed>
     */
    public function definitionDataProvider(): array
    {
        return [
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                'myProperty',
                '
                <Neos.Fusion:Loop items={presentationObject.myProperty}>
                    <Vendor.Site:Component.MyComponent presentationObject={item} />
                </Neos.Fusion:Loop>
            '
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent')),
                'myProperty',
                '
                <Neos.Fusion:Loop items={presentationObject.myProperty}>
                    <Vendor.Site:CustomType.MyComponent presentationObject={item} />
                </Neos.Fusion:Loop>
            '
            ],
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')),
                'myProperty',
                '
                <Neos.Fusion:Loop items={presentationObject.myProperty}>
                    <Vendor.Site:Custom.Type.MyComponent presentationObject={item} />
                </Neos.Fusion:Loop>
            '
            ]
        ];
    }
}
