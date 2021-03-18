<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeClass;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepositoryInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;
use Prophecy\Argument;
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
     * @var ObjectProphecy<PropTypeRepositoryInterface>
     */
    protected $propTypeRepository;

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

        $this->propTypeRepository = $this->prophet->prophesize(PropTypeRepositoryInterface::class);
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'string')
            ->willReturn(new PropType('string', 'string', 'string', false, PropTypeClass::primitive()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), '?string')
            ->willReturn(new PropType('string', 'string', 'string', true, PropTypeClass::primitive()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'HeadlineType')
            ->willReturn(new PropType('HeadlineType', 'HeadlineType', 'HeadlineType', false, PropTypeClass::value()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'HeadlineLook')
            ->willReturn(new PropType('HeadlineLook', 'HeadlineLook', 'HeadlineLook', false, PropTypeClass::value()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'ImageSourceHelperInterface')
            ->willReturn(new PropType('ImageSourceHelperInterface', 'ImageSourceHelperInterface', 'ImageSourceHelperInterface', false, PropTypeClass::globalValue()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'UriInterface')
            ->willReturn(new PropType('UriInterface', 'UriInterface', 'UriInterface', false, PropTypeClass::globalValue()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), '?Image')
            ->willReturn(new PropType('Image', 'Image', 'Image', true, PropTypeClass::Component()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), '?Text')
            ->willReturn(new PropType('Text', 'Text', 'Text', true, PropTypeClass::Component()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), '?Link')
            ->willReturn(new PropType('Link', 'Link', 'Link', true, PropTypeClass::Component()));
        $this->propTypeRepository
            ->findByType(Argument::any(), Argument::any(), 'array<Text>')
            ->willReturn(new PropType('Texts', 'Texts', 'Vendor\Site\Presentation\Text\Texts', false, PropTypeClass::Generic()));

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

        $this->inject($this->componentGenerator, 'propTypeRepository', $this->propTypeRepository->reveal());
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
                ['Text', ['content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/Text.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Text/Text.fusion'
                ]],
            'text in default package' =>
                ['Text', ['content:string'], null, [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Text/Text.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Text/TextInterface.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Text/TextFactory.php',
                    'vfs://DistributionPackages/Vendor.Default/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Default/Resources/Private/Fusion/Presentation/Component/Text/Text.fusion'
                ]],
            'headline' =>
                ['Headline', ['type:HeadlineType', 'look:HeadlineLook', 'content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Headline/Headline.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Headline/HeadlineInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Headline/HeadlineFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Headline/Headline.fusion'
                ]],
            'image' =>
                ['Image', ['src:ImageSourceHelperInterface', 'alt:string', 'title:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Image/Image.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Image/ImageInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Image/ImageFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Image/Image.fusion'
                ]],
            'link' =>
                ['Link', ['href:UriInterface', 'title:?string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Link/Link.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Link/LinkInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Link/LinkFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Link/Link.fusion'
                ]],
            'card' =>
                ['Card', ['image:?Image', 'text:?Text', 'link:?Link'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]],
            'fancyText' =>
                ['Text', ['text:?Text'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/Text.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/FancyComponent/Text/Text.fusion'
                ], 'FancyComponent'],
            'evenFancierText' =>
                ['Text', ['text:?Text'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/Text.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Even/FancierComponent/Text/Text.fusion'
                ], 'Even.FancierComponent'],
            'genericText' =>
                ['Text', ['content:string'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/Text.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/Texts.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Text/TextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Text/Text.fusion'
                ], 'Component', true],
            'withGenericText' =>
                ['WithGenericText', ['texts:array<Text>'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/WithGenericText/WithGenericText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/WithGenericText/WithGenericTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/WithGenericText/WithGenericTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/WithGenericText/WithGenericText.fusion'
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
