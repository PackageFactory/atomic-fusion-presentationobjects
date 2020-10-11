<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Value;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Value\ValueGenerator;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Test cases for ValueGenerator
 */
final class ValueGeneratorTest extends UnitTestCase
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
     * @var ValueGenerator
     */
    protected $valueGenerator;

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

        $this->valueGenerator = new ValueGenerator(new \DateTimeImmutable('@1602423895'));

        $this->inject($this->valueGenerator, 'packageResolver', $this->packageResolver->reveal());
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
     * @return array<string,array{string,string,string,string[],null|string}>
     */
    public function exampleProvider(): array
    {
        return [
            'headlinetype' =>
                ['Headline', 'HeadlineType', 'string', ['H1', 'H2', 'DIV'], 'Vendor.Site', [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Headline/HeadlineType.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Headline/HeadlineTypeIsInvalid.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Application/HeadlineTypeProvider.php',
                ]],
            'trafficlight' =>
                ['Crossing', 'TrafficLight', 'int', ['RED', 'YELLOW', 'GREEN'], null, [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Crossing/TrafficLight.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Crossing/TrafficLightIsInvalid.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Application/TrafficLightProvider.php',
                ]],
        ];
    }

    /**
     * @test
     * @group isolated
     * @dataProvider exampleProvider
     * @param string $componentName
     * @param string $name
     * @param string $type
     * @param string[] $values
     * @param string|null $packageKey
     * @param string[] $expectedFileNames
     * @return void
     */
    public function generatesValues(string $componentName, string $name, string $type, array $values, ?string $packageKey, array $expectedFileNames): void
    {
        $this->valueGenerator->generateValue($componentName, $name, $type, $values, $packageKey);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
