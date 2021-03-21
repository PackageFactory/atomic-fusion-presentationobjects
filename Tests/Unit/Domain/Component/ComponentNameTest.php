<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Eel\Package;
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
     * @dataProvider classNameProvider
     */
    public function testFromClassName(string $className, ComponentName $expectedName): void
    {
        Assert::assertEquals($expectedName, ComponentName::fromClassName($className));
    }

    public function classNameProvider(): array
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
     * @dataProvider fusionNameProvider
     */
    public function testGetFullyQualifiedFusionName(ComponentName $subject, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getFullyQualifiedFusionName());
    }

    public function fusionNameProvider(): array
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

    public function phpNamespaceProvider(): array
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

    public function factoryNameProvider(): array
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

    public function interfaceNameProvider(): array
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

    public function helperNameProvider(): array
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
    public function testGetPhpFilePath(ComponentName $subject, string $packagePath, string $expectedValue)
    {
        Assert::assertSame($expectedValue, $subject->getPhpFilePath($packagePath));
    }

    public function phpFilePathProvider(): array
    {
        return [
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                'DistributionPackages/Vendor.Site/Classes/Presentation/Component/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('CustomType'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
                'DistributionPackages/Vendor.Site/Classes/Presentation/CustomType/MyComponent'
            ],
            [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Custom.Type'), 'MyComponent'),
                'DistributionPackages/Vendor.Site',
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

    public function fusionFilePathProvider(): array
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
