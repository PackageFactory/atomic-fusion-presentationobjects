<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the ComponentName value object
 */
final class ComponentNameTest extends UnitTestCase
{
    /**
     * @dataProvider inputProvider
     */
    public function testFromInput(string $input, PackageKey $defaultPackageKey, ComponentName $expectedName): void
    {
        Assert::assertEquals($expectedName, ComponentName::fromInput($input, $defaultPackageKey));
    }

    /**
     * @return array<array{string,PackageKey,ComponentName}>
     */
    public static function inputProvider(): array
    {
        $defaultPackageKey = new PackageKey('Vendor.Default');

        return [
            [
                'MyComponent',
                $defaultPackageKey,
                new ComponentName($defaultPackageKey, FusionNamespace::default(), 'MyComponent')
            ],
            [
                'Custom.Type.MyComponent',
                $defaultPackageKey,
                new ComponentName($defaultPackageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent')
            ],
            [
                'Vendor.Site:MyComponent',
                $defaultPackageKey,
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent')
            ],
            [
                'Vendor.Site:Custom.Type.MyComponent',
                $defaultPackageKey,
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent')
            ]
        ];
    }

    /**
     * @dataProvider classNameProvider
     */
    public function testFromClassName(string $className, ComponentName $expectedName): void
    {
        Assert::assertEquals($expectedName, ComponentName::fromClassName($className));
    }

    /**
     * @return array<mixed>
     */
    public static function classNameProvider(): array
    {
        $packageKey = new PackageKey('Vendor.Site');
        return [
            [
                'Vendor\Site\Presentation\Component\MyComponent\MyComponent',
                new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyComponent')
            ],
            [
                'Vendor\Site\Presentation\CustomType\MyComponent\MyComponent',
                new ComponentName($packageKey, FusionNamespace::fromString('CustomType'), 'MyComponent')
            ],
            [
                'Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponent',
                new ComponentName($packageKey, FusionNamespace::fromString('Custom.Type'), 'MyComponent')
            ],
            [
                'Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum',
                new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'MyStringEnum')
            ],
            [
                'Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponents',
                new ComponentName($packageKey, FusionNamespace::fromString('Component'), 'AnotherComponent')
            ]
        ];
    }

    /**
     * @dataProvider fusionPathProvider
     */
    public function testFromFusionPath(string $className, ComponentName $expectedName): void
    {
        Assert::assertEquals($expectedName, ComponentName::fromFusionPath($className));
    }

    /**
     * @return array<mixed>
     */
    public static function fusionPathProvider(): array
    {
        return [
            [
                '/<Acme.Site:Block.Text>',
                new ComponentName(
                    new PackageKey('Acme.Site'),
                    FusionNamespace::fromString('Block'),
                    'Text'
                )
            ],
            [
                '/<Acme.Site:Block.Fancy.Text>',
                new ComponentName(
                    new PackageKey('Acme.Site'),
                    FusionNamespace::fromString('Block.Fancy'),
                    'Text'
                )
            ],
            [
                '/<Acme.Site:Layout.SomeComponent>/__meta/styleguide/useCases/introduction/props<Neos.Fusion:DataStructure>/text<Acme.Site:Block.Text>',
                new ComponentName(
                    new PackageKey('Acme.Site'),
                    FusionNamespace::fromString('Block'),
                    'Text'
                )
            ],
            [
                '/<Acme.Site:Layout.SomeComponent>/__meta/styleguide/useCases/introduction/props<Neos.Fusion:DataStructure>/text<Acme.Site:Block.Fancy.Text>',
                new ComponentName(
                    new PackageKey('Acme.Site'),
                    FusionNamespace::fromString('Block.Fancy'),
                    'Text'
                )
            ]
        ];
    }

    /**
     * @dataProvider fusionNameProvider
     */
    public function testGetFullyQualifiedFusionName(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getFullyQualifiedFusionName());
    }

    /**
     * @return array<mixed>
     */
    public static function fusionNameProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'Vendor.Site:Component.MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'Vendor.Site:CustomType.MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'Vendor.Site:Custom.Type.MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider phpNamespaceProvider
     */
    public function testGetPhpNamespace(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getPhpNamespace());
    }

    /**
     * @return array<mixed>
     */
    public static function phpNamespaceProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'Vendor\Site\Presentation\Component\MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'Vendor\Site\Presentation\CustomType\MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'Vendor\Site\Presentation\Custom\Type\MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider factoryNameProvider
     */
    public function testGetFullyQualifiedFactoryName(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getFullyQualifiedFactoryName());
    }

    /**
     * @return array<mixed>
     */
    public static function factoryNameProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'Vendor\Site\Presentation\Component\MyComponent\MyComponentFactory'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'Vendor\Site\Presentation\CustomType\MyComponent\MyComponentFactory'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponentFactory'
            ]
        ];
    }

    /**
     * @dataProvider interfaceNameProvider
     */
    public function testGetFullyQualifiedInterfaceName(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getFullyQualifiedInterfaceName());
    }

    /**
     * @return array<mixed>
     */
    public static function interfaceNameProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'Vendor\Site\Presentation\Component\MyComponent\MyComponentInterface'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'Vendor\Site\Presentation\CustomType\MyComponent\MyComponentInterface'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'Vendor\Site\Presentation\Custom\Type\MyComponent\MyComponentInterface'
            ]
        ];
    }

    /**
     * @dataProvider helperNameProvider
     */
    public function testGetHelperName(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getHelperName());
    }

    /**
     * @return array<mixed>
     */
    public static function helperNameProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'Site.MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'Site.MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'Site.MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider phpFilePathProvider
     */
    public function testGetPhpFilePath(ComponentName $subject, string $packagePath, bool $colocate, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getPhpFilePath($packagePath, $colocate));
    }

    /**
     * @return array<mixed>
     */
    public static function phpFilePathProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                false,
                'DistributionPackages/Vendor.Site/Classes/Presentation/Component/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                false,
                'DistributionPackages/Vendor.Site/Classes/Presentation/CustomType/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                false,
                'DistributionPackages/Vendor.Site/Classes/Presentation/Custom/Type/MyComponent'
            ]
        ];
    }

    /**
     * @dataProvider fusionFilePathProvider
     */
    public function testGetFusionFilePath(ComponentName $subject, string $packagePath, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getFusionFilePath($packagePath));
    }

    /**
     * @return array<mixed>
     */
    public static function fusionFilePathProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                'DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                'DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/CustomType/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                'DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Custom/Type/MyComponent'
            ]
        ];
    }
}
