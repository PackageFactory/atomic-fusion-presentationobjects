<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Dumper as YamlWriter;

/**
 * The component generator domain service
 *
 * @Flow\Scope("singleton")
 */
final class ComponentGenerator
{
    /**
     * @Flow\Inject(lazy=false)
     * @var PropTypeRepository
     */
    protected $propTypeRepository;

    /**
     * @Flow\Inject
     * @var PackageResolverInterface
     */
    protected $packageResolver;

    /**
     * @param string $componentName
     * @param array|string[] $serializedProps
     * @param string|null $packageKey
     * @param FusionNamespace|null $namespace
     * @param bool $generic
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generateComponent(string $componentName, array $serializedProps, ?string $packageKey = null, ?FusionNamespace $namespace = null, bool $generic = false): void
    {
        $package = $this->packageResolver->resolvePackage($packageKey);
        $component = Component::fromInput(
            $package->getPackageKey(),
            $componentName,
            $serializedProps,
            $this->propTypeRepository,
            $namespace ?: FusionNamespace::default(),
            $generic
        );

        $packagePath = $package->getPackagePath();
        $classPath = $packagePath . 'Classes/Presentation/' . $componentName;
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        $fusionPath = $packagePath . 'Resources/Private/Fusion/Presentation/' . $component->getFusionNamespace()->toFilePath() . '/' . $componentName;
        if (!file_exists($fusionPath)) {
            Files::createDirectoryRecursively($fusionPath);
        }
        file_put_contents($component->getInterfacePath($packagePath), $component->getInterfaceContent());
        file_put_contents($component->getClassPath($packagePath), $component->getClassContent());
        file_put_contents($component->getFactoryPath($packagePath), $component->getFactoryContent());
        file_put_contents($component->getFusionPath($packagePath), $component->getFusionContent());
        if ($generic) {
            file_put_contents($component->getGenericPath($packagePath), $component->getGenericContent());
        }
        $this->registerFactory($package, $component);
    }

    /**
     * @param FlowPackageInterface $package
     * @param Component $component
     * @return void
     */
    private function registerFactory(FlowPackageInterface $package, Component $component): void
    {
        $configurationPath = $package->getPackagePath() . 'Configuration/';
        $configurationFilePath = $configurationPath . 'Settings.PresentationHelpers.yaml';
        if (!file_exists($configurationFilePath)) {
            Files::createDirectoryRecursively($configurationPath);
            $configuration = ['Neos' => ['Fusion' => ['defaultContext' => [
                $component->getHelperName() => $component->getFactoryName()
            ]]]];
        } else {
            $parser = new YamlParser();
            $configuration = $parser->parseFile($configurationFilePath);
            $configuration['Neos']['Fusion']['defaultContext'][$component->getHelperName()] = $component->getFactoryName();
        }

        $writer = new YamlWriter(2);
        file_put_contents($configurationFilePath, $writer->dump($configuration, 100));
    }
}
