<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\SimpleFileWriter;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Test cases for ComponentGenerator
 */
final class ComponentGeneratorTest extends UnitTestCase
{
    use MatchesSnapshots;

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

        $this->componentGenerator = new ComponentGenerator(
            new SimpleFileWriter()
        );
    }

    /**
     * @return array<string,array{ComponentName,string[],string,bool,bool,string[]}>
     */
    public function exampleProvider(): array
    {
        return [
            'text' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'NewText'),
                ['content:string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ]
            ],
            'text in default package' => [
                new ComponentName(new PackageKey('Vendor.Default'), FusionNamespace::default(), 'NewText'),
                ['content:string'],
                'vfs://DistributionPackages/Vendor.Default/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Default/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Default/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ]
            ],
            'headline' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Headline'),
                ['type:HeadlineType', 'look:HeadlineLook', 'content:string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/Headline.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Headline/Headline.fusion'
                ]
            ],
            'image' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Image'),
                ['src:ImageSource', 'alt:string', 'title:?string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/Image.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/ImageInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Image/ImageFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Image/Image.fusion'
                ]
            ],
            'link' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'NewLink'),
                ['href:Uri', 'title:?string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLink.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLinkInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewLink/NewLinkFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewLink/NewLink.fusion'
                ]
            ],
            'card' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Card'),
                ['image:?ImageSource', 'text:?Text', 'link:?Link'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]
            ],
            'fancyText' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('FancyComponent'), 'NewText'),
                ['text:?string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/FancyComponent/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/FancyComponent/NewText/NewText.fusion'
                ]
            ],
            'evenFancierText' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::fromString('Even.FancierComponent'), 'NewText'),
                ['text:?string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Even/FancierComponent/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Even/FancierComponent/NewText/NewText.fusion'
                ],
            ],
            'textWithArray' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'NewText'),
                ['content:string'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                true,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewText.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTexts.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/NewText/NewTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/NewText/NewText.fusion'
                ]
            ],
            'withTextArray' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'WithTextArray'),
                ['texts:array<Text>'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArray.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArrayInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/WithTextArray/WithTextArrayFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/WithTextArray/WithTextArray.fusion'
                ]
            ],
            'cardWithSharedText' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Card'),
                ['image:?ImageSource', 'text:?Vendor.Shared:Text', 'link:?Link'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]
            ],
            'cardWithSharedCustomNamespacedText' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Card'),
                ['image:?ImageSource', 'text:?Vendor.Shared:Custom.Type.Text', 'link:?Link'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]
            ],
            'cardWithSharedTexts' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Card'),
                ['image:?ImageSource', 'text:array<Vendor.Shared:Text>', 'link:?Link'],
                'vfs://DistributionPackages/Vendor.Site/',
                false,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/Card.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Card/CardFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Card/Card.fusion'
                ]
            ],
            'colocatedText' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'ColocatedText'),
                ['content:string'],
                'vfs://DistributionPackages/Vendor.Site/',
                true,
                false,
                [
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/ColocatedText/ColocatedText.php',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/ColocatedText/ColocatedTextInterface.php',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/ColocatedText/ColocatedTextFactory.php',
                    'vfs://DistributionPackages/Vendor.Site/Configuration/Settings.PresentationHelpers.yaml',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/ColocatedText/ColocatedText.fusion'
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider exampleProvider
     * @param ComponentName $componentName
     * @param string[] $serializedProps
     * @param string $packagePath
     * @param bool $listable
     * @param string[] $expectedFileNames
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generatesComponents(ComponentName $componentName, array $serializedProps, string $packagePath, bool $colocate, bool $listable, array $expectedFileNames): void
    {
        $this->componentGenerator->generateComponent($componentName, $serializedProps, $packagePath, $listable);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
