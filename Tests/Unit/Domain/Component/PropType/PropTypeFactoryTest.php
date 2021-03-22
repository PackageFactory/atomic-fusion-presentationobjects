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
     * @param PackageKey $packageKey
     * @param string $componentName
     * @param string $inputString
     * @param PropTypeInterface $expectedPropType
     * @return void
     */
    public function testFromInputString(PackageKey $packageKey, string $componentName, string $inputString, PropTypeInterface $expectedPropType): void
    {
        Assert::assertEquals($expectedPropType, PropTypeFactory::fromInputString($packageKey, $componentName, $inputString));
    }

    /**
     * @dataProvider invalidInputStringProvider
     * @param PackageKey $packageKey
     * @param string $componentName
     * @param string $inputString
     * @return void
     */
    public function testFromInputStringCatchesInvalidInputs(PackageKey $packageKey, string $componentName, string $inputString): void
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
                $packageKey,
                'MyNewComponent',
                'bool',
                new BoolPropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?bool',
                new BoolPropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'string',
                new StringPropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?string',
                new StringPropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'int',
                new IntPropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?int',
                new IntPropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'float',
                new FloatPropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?float',
                new FloatPropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'Uri',
                new UriPropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?Uri',
                new UriPropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'ImageSource',
                new ImageSourcePropType(false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?ImageSource',
                new ImageSourcePropType(true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'MyStringEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringEnum', false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?MyStringEnum',
                new EnumPropType('Vendor\\Site\\Presentation\\Component\\MyNewComponent\\MyStringEnum', true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'), true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), false)
            ],
            [
                $packageKey,
                'MyNewComponent',
                '?Custom.Type.MyComponent',
                new ComponentPropType(new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent'), true)
            ],
            [
                $packageKey,
                'MyNewComponent',
                'array<MyComponent>',
                new ComponentArrayPropType(new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent'))
            ],
            [
                $packageKey,
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
        $packageKey = new PackageKey('Vendor.Site');
        return [
            [
                $packageKey,
                'MyNewComponent',
                'integer'
            ],
            [
                $packageKey,
                'MyNewComponent',
                'array<MyStringEnum>'
            ],
            [
                $packageKey,
                'MyNewComponent',
                'UndefinedComponent'
            ],
            [
                $packageKey,
                'MyNewComponent',
                'Undefined.Type.MyComponent'
            ],
            [
                $packageKey,
                'MyNewComponent',
                'UndefinedEnum'
            ],
            [
                $packageKey,
                'InvalidComponent',
                'InvalidEnum'
            ],
            [
                $packageKey,
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
