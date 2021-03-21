<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumGenerator;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Test cases for EnumGenerator
 */
final class EnumGeneratorTest extends UnitTestCase
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

        $this->enumGenerator = new EnumGenerator(new \DateTimeImmutable('@1602423895'));

        $this->inject($this->enumGenerator, 'packageResolver', $this->packageResolver->reveal());
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
                ['Headline', 'HeadlineType', 'string', ['h1', 'h2', 'div'], 'Vendor.Site', null, [
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineType.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Presentation/Component/Headline/HeadlineTypeIsInvalid.php',
                    'vfs://DistributionPackages/Vendor.Site/Classes/Application/HeadlineTypeProvider.php',
                ]],
            'trafficlight' =>
                ['Crossing', 'TrafficLight', 'int', ['red:1', 'yellow:2', 'green:3'], null, null, [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/Crossing/TrafficLight.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Component/Crossing/TrafficLightIsInvalid.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Application/TrafficLightProvider.php',
                ]],
            'duration' =>
                ['Crossing', 'Duration', 'float', ['short:1.2', 'medium:2.4', 'long:3.6'], null, FusionNamespace::fromString('Custom.Type'), [
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Custom/Type/Crossing/Duration.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Presentation/Custom/Type/Crossing/DurationIsInvalid.php',
                    'vfs://DistributionPackages/Vendor.Default/Classes/Application/DurationProvider.php',
                ]]
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
    public function generatesEnums(string $componentName, string $name, string $type, array $values, ?string $packageKey, ?FusionNamespace $fusionNamespace, array $expectedFileNames): void
    {
        $this->enumGenerator->generateEnum($componentName, $name, $type, $values, $packageKey, $fusionNamespace);

        foreach ($expectedFileNames as $fileName) {
            $this->assertFileExists($fileName);
            $this->assertMatchesSnapshot(file_get_contents($fileName));
        }
    }
}
