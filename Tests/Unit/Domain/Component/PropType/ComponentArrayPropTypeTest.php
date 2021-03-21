<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

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
     */
    public function testGetSimpleName(ComponentArrayPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getSimpleName());
    }

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
     */
    public function testGetUseStatement(ComponentArrayPropType $subject, string $expectedName): void
    {
        Assert::assertSame($expectedName, $subject->getUseStatement());
    }

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
     */
    public function testGetType(ComponentArrayPropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

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
     */
    public function testGetStyleGuideValue(ComponentArrayPropType $subject, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getStyleGuideValue());
    }

    public function styleGuideValueProvider(): array
    {
        return [
            [
                new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')),
                '{
            {
                text = \'Text\'
                other {
                    number = 4711
                }
            },
            {
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
            {
                text = \'Text\'
                other {
                    number = 4711
                }
            },
            {
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
            {
                text = \'Text\'
                other {
                    number = 4711
                }
            },
            {
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
     */
    public function testGetDefinitionData(ComponentArrayPropType $subject, string $propName, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getDefinitionData($propName));
    }

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
