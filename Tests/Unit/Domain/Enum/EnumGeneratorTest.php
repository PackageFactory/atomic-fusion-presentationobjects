<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

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
     */
    public function setUpComponentGeneratorTest(): void
    {
        vfsStream::setup('DistributionPackages', null, [
            'Vendor.Site' => [],
            'Vendor.Default' => [],
        ]);

        $this->enumGenerator = new EnumGenerator(
            new SimpleFileWriter()
        );
    }

    /**
     * @return array<string,array{ComponentName,string,string,string[],string,string[],bool}>
     */
    public static function exampleProvider(): array
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
                ],
                true
            ],
        ];
    }

    /**
     * @group isolated
     * @dataProvider exampleProvider
     * @param string[] $values
     * @param string[] $expectedFileNames
     */
    public function testGeneratesEnums(ComponentName $componentName, string $name, string $type, array $values, string $packagePath, array $expectedFileNames, bool $colocate): void
    {
        $this->enumGenerator->generateEnum($componentName, $name, $type, $values, $packagePath, $colocate);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
