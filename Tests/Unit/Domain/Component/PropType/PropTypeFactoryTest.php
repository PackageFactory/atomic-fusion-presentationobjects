<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\SlotPropType;
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
     * @param ComponentName $componentName
     * @param string $inputString
     * @param PropTypeInterface $expectedPropType
     * @return void
     */
    public function testFromInputString(ComponentName $componentName, string $inputString, PropTypeInterface $expectedPropType): void
    {
        Assert::assertEquals($expectedPropType, PropTypeFactory::fromInputString($componentName, $inputString));
    }

    /**
     * @dataProvider invalidInputStringProvider
     * @param ComponentName $componentName
     * @param string $inputString
     * @return void
     */
    public function testFromInputStringCatchesInvalidInputs(ComponentName $componentName, string $inputString): void
    {
        $this->expectException(PropTypeIsInvalid::class);
        PropTypeFactory::fromInputString($componentName, $inputString);
    }

    /**
     * @return array<mixed>
     */
    public function validInputStringProvider(): array
    {
        $packageKey = new PackageKey('Vendor.Site');
        $componentName = new ComponentName($packageKey, FusionNamespace::default(), 'MyNewComponent');
        return [
            [
                $componentName,
                'bool',
                new BoolPropType(false)
            ],
            [
                $componentName,
                '?bool',
                new BoolPropType(true)
            ],
            [
                $componentName,
                'string',
                new StringPropType(false)
            ],
            [
                $componentName,
                '?string',
                new StringPropType(true)
            ],
            [
                $componentName,
                'int',
                new IntPropType(false)
            ],
            [
                $componentName,
                '?int',
                new IntPropType(true)
            ],
            [
                $componentName,
                'float',
                new FloatPropType(false)
            ],
            [
                $componentName,
                '?float',
                new FloatPropType(true)
            ],
            [
                $componentName,
                'Uri',
                new UriPropType(false)
            ],
            [
                $componentName,
                '?Uri',
                new UriPropType(true)
            ],
            [
                $componentName,
                'ImageSource',
                new ImageSourcePropType(false)
            ],
            [
                $componentName,
                '?ImageSource',
                new ImageSourcePropType(true)
            ],
            [
                $componentName,
                'slot',
                new SlotPropType(false)
            ],
            [
                $componentName,
                '?slot',
                new SlotPropType(true)
            ],
            [
                $componentName,
                'MyStringPseudoEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringPseudoEnum', false)
            ],
            [
                $componentName,
                '?MyStringPseudoEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringPseudoEnum', true)
            ],
            [
                $componentName,
                'MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), false)
            ],
            [
                $componentName,
                '?MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), true)
            ],
            [
                $componentName,
                'Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false)
            ],
            [
                $componentName,
                '?Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true)
            ],
            [
                $componentName,
                'array<MyComponent>',
                new ComponentArrayPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'))
            ],
            [
                $componentName,
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
        $componentName = new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyNewComponent');
        return [
            [
                $componentName,
                'integer'
            ],
            [
                $componentName,
                'array<MyStringEnum>'
            ],
            [
                $componentName,
                'UndefinedComponent'
            ],
            [
                $componentName,
                'Undefined.Type.MyComponent'
            ],
            [
                $componentName,
                'UndefinedEnum'
            ],
            [
                $componentName,
                'InvalidEnum'
            ],
            [
                $componentName,
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
                case 'slotProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new SlotPropType(false)
                    ];
                    break;
                case 'nullableSlotProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new SlotPropType(true)
                    ];
                    break;
                case 'enumProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new EnumPropType('Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum', false)
                    ];
                    break;
                case 'nullableEnumProp':
                    $reflectionPropertyCases[] = [
                        $reflectionProperty,
                        new EnumPropType('Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum', true)
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
