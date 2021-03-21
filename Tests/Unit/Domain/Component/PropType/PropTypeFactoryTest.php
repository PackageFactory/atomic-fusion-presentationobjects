<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Vendor\Site\Presentation\Component\MyReflectionComponent\MyReflectionComponent;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\BoolPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ComponentArrayPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ComponentPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\EnumPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\FloatPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ImageSourcePropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IntPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeIsInvalid;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\StringPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\UriPropType;
use PHPUnit\Framework\Assert;

/**
 * Test for the PropTypeFactory
 */
final class PropTypeFactoryTest extends UnitTestCase
{
    /**
     * @dataProvider validInputStringProvider
     * @param string $packageKey
     * @param string $componentName
     * @param string $inputString
     * @param PropTypeInterface $expectedPropType
     * @return void
     */
    public function testFromInputString(string $packageKey, string $componentName, string $inputString, PropTypeInterface $expectedPropType): void
    {
        Assert::assertEquals($expectedPropType, PropTypeFactory::fromInputString($packageKey, $componentName, $inputString));
    }

    /**
     * @dataProvider invalidInputStringProvider
     * @param string $packageKey
     * @param string $componentName
     * @param string $inputString
     * @return void
     */
    public function testFromInputStringCatchesInvalidInputs(string $packageKey, string $componentName, string $inputString): void
    {
        $this->expectException(PropTypeIsInvalid::class);
        PropTypeFactory::fromInputString($packageKey, $componentName, $inputString);
    }

    /**
     * @return array<mixed>
     */
    public function validInputStringProvider(): array
    {
        $packageKey = new PackageKey('Vendor.Site');
        return [
            [
                'Vendor.Site',
                'MyNewComponent',
                'bool',
                new BoolPropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?bool',
                new BoolPropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'string',
                new StringPropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?string',
                new StringPropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'int',
                new IntPropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?int',
                new IntPropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'float',
                new FloatPropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?float',
                new FloatPropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'Uri',
                new UriPropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?Uri',
                new UriPropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'ImageSource',
                new ImageSourcePropType(false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?ImageSource',
                new ImageSourcePropType(true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'MyStringEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringEnum', false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?MyStringEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringEnum', true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                '?Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true)
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'array<MyComponent>',
                new ComponentArrayPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'))
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'array<Custom.Type.MyComponent>',
                new ComponentArrayPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'))
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public function invalidInputStringProvider(): array
    {
        return [
            [
                'Vendor.Site',
                'MyNewComponent',
                'integer'
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'array<MyStringEnum>'
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'UndefinedComponent'
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'Undefined.Type.MyComponent'
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'UndefinedEnum'
            ],
            [
                'Vendor.Site',
                'InvalidComponent',
                'InvalidEnum'
            ],
            [
                'Vendor.Site',
                'MyNewComponent',
                'InvalidComponent'
            ]
        ];
    }


    /**
     * @dataProvider validReflectionPropertyProvider
     * @param \ReflectionProperty $reflectionProperty
     * @param PropTypeInterface $expectedPropType
     * @return void
     */
    public function testFromReflectionProperty(\ReflectionProperty $reflectionProperty, PropTypeInterface $expectedPropType): void
    {
        Assert::assertEquals($expectedPropType, PropTypeFactory::fromReflectionProperty($reflectionProperty));
    }

    /**
     * @return array<mixed>
     */
    public function validReflectionPropertyProvider(): array
    {
        $reflectionPropertyCases = [];
        $reflectionClass = new \ReflectionClass(MyReflectionComponent::class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            switch ($reflectionProperty->getName()) {
                case 'stringProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new StringPropType(false)
                    ];
                    break;
                case 'nullableStringProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new StringPropType(true)
                    ];
                    break;
                case 'intProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new IntPropType(false)
                    ];
                    break;
                case 'nullableIntProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new IntPropType(true)
                    ];
                    break;
                case 'floatProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new FloatPropType(false)
                    ];
                    break;
                case 'nullableFloatProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new FloatPropType(true)
                    ];
                    break;
                case 'boolProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new BoolPropType(false)
                    ];
                    break;
                case 'nullableBoolProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new BoolPropType(true)
                    ];
                    break;
                case 'uriProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new UriPropType(false)
                    ];
                    break;
                case 'nullableUriProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new UriPropType(true)
                    ];
                    break;
                case 'imageSourceProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new ImageSourcePropType(false)
                    ];
                    break;
                case 'nullableImageSourceProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new ImageSourcePropType(true)
                    ];
                    break;
                case 'enumProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new EnumPropType('Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum', false)
                    ];
                    break;
                case 'nullableEnumProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new EnumPropType('Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum', true)
                    ];
                    break;
                case 'componentProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Component'), 'AnotherComponent'), false)
                    ];
                    break;
                case 'nullableComponentProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new ComponentPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Component'), 'AnotherComponent'), true)
                    ];
                    break;
                case 'componentArrayProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new ComponentArrayPropType(new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Component'), 'AnotherComponent'))
                    ];
                    break;
            }
        }

        return $reflectionPropertyCases;
    }

    /**
     * @dataProvider invalidReflectionPropertyProvider
     * @param \ReflectionProperty $reflectionProperty
     * @return void
     */
    public function testFromReflectionPropertyCatchesInvalidReflectionProperties(\ReflectionProperty $reflectionProperty): void
    {
        $this->expectException(PropTypeIsInvalid::class);
        PropTypeFactory::fromReflectionProperty($reflectionProperty);
    }

    /**
     * @return array<mixed>
     */
    public function invalidReflectionPropertyProvider(): array
    {
        $reflectionClass = new \ReflectionClass(MyReflectionComponent::class);
        return [
            [
                $reflectionClass->getProperty('dateProp')
            ]
        ];
    }
}
