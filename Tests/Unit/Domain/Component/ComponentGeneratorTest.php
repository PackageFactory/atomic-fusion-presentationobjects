<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Test cases for ComponentGenerator
 */
final class ComponentGeneratorTest extends UnitTestCase
{
    use MatchesSnapshots;

    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @var ObjectProphecy<FlowPackageInterface>
     */
    protected $sitePackage;

    /**
     * @var ObjectProphecy<FlowPackageInterface>
     */
    protected $defaultPackage;

    /**
     * @var ObjectProphecy<PackageResolverInterface>
     */
    protected $packageResolver;

    /**
     * @var ComponentGenerator
     */
    protected $componentGenerator;

    /**
     * @before
     * @return void
     */
    public function setUpComponentGeneratorTest(): void
    {
        vfsStream::setup('DistributionPackages', null, [
            'Vendor.Site' => [],
            'Vendor.Default' => [
                'Configuration' => [
                    'Settings.PresentationHelpers.yaml' => join(PHP_EOL, [
                        'Neos:',
                        '  Fusion:',
                        '    defaultContext:',
                        '      Existing.Helper: Some\OtherPackage\Existing\HelperFactory',
                    ])
                ]
            ],
        ]);

        $this->prophet = new Prophet();

        $this->sitePackage = $this->prophet->prophesize(FlowPackageInterface::class);
        $this->sitePackage
            ->getPackageKey()
            ->willReturn('Vendor.Site');
        $this->sitePackage
            ->getPackagePath()
            ->willReturn('vfs://DistributionPackages/Vendor.Site/');

        $this->defaultPackage = $this->prophet->prophesize(FlowPackageInterface::class);
        $this->defaultPackage
            ->getPackageKey()
            ->willReturn('Vendor.Default');
        $this->defaultPackage
            ->getPackagePath()
            ->willReturn('vfs://DistributionPackages/Vendor.Default/');

        $this->packageResolver = $this->prophet->prophesize(PackageResolverInterface::class);
        $this->packageResolver
            ->resolvePackage('Vendor.Site')
            ->willReturn($this->sitePackage);
        $this->packageResolver
            ->resolvePackage(null)
            ->willReturn($this->defaultPackage);

        $this->componentGenerator = new ComponentGenerator();

        $this->inject($this->componentGenerator, 'packageResolver', $this->packageResolver->reveal());
    }

    /**
     * @after
     * @return void
     */
    public function tearDownComponentGeneratorTest(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @return array<string,array{string,string[],null|string}>
     */
    public function exampleProvider(): array
    {
        return [
            'text' =>
                ['NewText', ['content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ]],
            'text in default package' =>
                ['NewText', ['content:string'], null, [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Default/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Default/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ]],
            'headline' =>
                ['Headline', ['type:HeadlineType', 'look:HeadlineLook', 'content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/Headline.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Headline/Headline.fusion'
                ]],
            'image' =>
                ['Image', ['src:ImageSource', 'alt:string', 'title:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/Image.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/ImageInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/ImageFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Image/Image.fusion'
                ]],
            'link' =>
                ['NewLink', ['href:Uri', 'title:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLink.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLinkInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLinkFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewLink/NewLink.fusion'
                ]],
            'card' =>
                ['Card', ['image:?ImageSource', 'text:?Text', 'link:?Link'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]],
            'fancyText' =>
                ['NewText', ['text:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/FancyComponent/NewText/NewText.fusion'
                ], 'FancyComponent'],
            'evenFancierText' =>
                ['NewText', ['text:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Even/FancierComponent/NewText/NewText.fusion'
                ], 'Even.FancierComponent'],
            'textWithArray' =>
                ['NewText', ['content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTexts.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ], 'Component', true],
            'withTextArray' =>
                ['WithTextArray', ['texts:array<Text>'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArray.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArrayInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArrayFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/WithTextArray/WithTextArray.fusion'
                ]],
        ];
    }

    /**
     * @test
     * @dataProvider exampleProvider
     * @param string $componentName
     * @param string[] $serializedProps
     * @param null|string $packageKey
     * @param string[] $expectedFileNames
     * @param string $fusionNamespace
     * @param bool $generic
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generatesComponents(string $componentName, array $serializedProps, ?string $packageKey, array $expectedFileNames, string $fusionNamespace = 'Component', bool $generic = false): void
    {
        $this->componentGenerator->generateComponent($componentName, $serializedProps, $packageKey, FusionNamespace::fromString($fusionNamespace), $generic);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
