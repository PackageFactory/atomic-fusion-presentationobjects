<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\SimpleFileWriter;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Test cases for EnumGenerator
 */
final class EnumGeneratorTest extends UnitTestCase
{
    use MatchesSnapshots;

    /**
     * @var EnumGenerator
     */
    protected $enumGenerator;

    /**
     * @before
     * @return void
     */
    public function setUpComponentGeneratorTest(): void
    {
        vfsStream::setup('DistributionPackages', null, [
            'Vendor.Site' => [],
            'Vendor.Default' => [],
        ]);

        $this->enumGenerator = new EnumGenerator(
            new \DateTimeImmutable('@1602423895'),
            new SimpleFileWriter()
        );
    }

    /**
     * @return array<string,array{ComponentName,string,string,string[],string,string[],bool}>
     */
    public function exampleProvider(): array
    {
        return [
            'headlinetype' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Headline'),
                'HeadlineType',
                'string',
                ['h1', 'h2', 'div'],
                'vfs://DistributionPackages/Vendor.Site/',
                [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineType.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineTypeIsInvalid.php'
                ],
                false
            ],
            'trafficlight' => [
                new ComponentName(new PackageKey('Vendor.Default'), FusionNamespace::default(), 'Crossing'),
                'TrafficLight',
                'int',
                ['red:1', 'yellow:2', 'green:3'],
                'vfs://DistributionPackages/Vendor.Default/',
                [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/Crossing/TrafficLight.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/Crossing/TrafficLightIsInvalid.php'
                ],
                false
            ],
            'coLocatedHeadlineType' => [
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'Headline'),
                'HeadlineType',
                'string',
                ['h1', 'h2', 'div'],
                'vfs://DistributionPackages/Vendor.Site/',
                [
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Headline/HeadlineType.php',
                    'vfs://DistributionPackages/Vendor.Site/Resources/Private/Fusion/Presentation/Component/Headline/HeadlineTypeIsInvalid.php'
                ],
                true
            ],
        ];
    }

    /**
     * @test
     * @group isolated
     * @dataProvider exampleProvider
     * @param ComponentName $componentName
     * @param string $name
     * @param string $type
     * @param string[] $values
     * @param string $packagePath
     * @param string[] $expectedFileNames
     * @param bool $colocate
     * @return void
     */
    public function generatesEnums(ComponentName $componentName, string $name, string $type, array $values, string $packagePath, array $expectedFileNames, bool $colocate): void
    {
        $this->enumGenerator->generateEnum($componentName, $name, $type, $values, $packagePath, $colocate);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
