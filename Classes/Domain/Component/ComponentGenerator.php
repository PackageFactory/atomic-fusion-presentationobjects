<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
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
     * @Flow\Inject
     * @var PackageResolverInterface
     */
    protected $packageResolver;

    /**
     * @param string $name
     * @param string $componentName
     * @param array|string[] $serializedProps
     * @param string|null $packageKey
     * @param FusionNamespace|null $namespace
     * @param bool $listable
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generateComponent(string $name, array $serializedProps, ?string $packageKey = null, ?FusionNamespace $namespace = null, bool $listable = false): void
    {
        $package = $this->packageResolver->resolvePackage($packageKey);
        $componentName = new ComponentName(
            PackageKey::fromPackage($package),
            $namespace ?: FusionNamespace::default(),
            $name
        );
        $props = Props::fromInputArray($package->getPackageKey(), $name, $serializedProps);
        $component = new Component($componentName, $props, $listable);

        $packagePath = $package->getPackagePath();
        $classPath = $componentName->getPhpFilePath($packagePath);
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        $fusionPath = $componentName->getFusionFilePath($packagePath);
        if (!file_exists($fusionPath)) {
            Files::createDirectoryRecursively($fusionPath);
        }
        file_put_contents($componentName->getInterfacePath($packagePath), $component->getInterfaceContent());
        file_put_contents($componentName->getClassPath($packagePath), $component->getClassContent());
        file_put_contents($componentName->getFactoryPath($packagePath), $component->getFactoryContent());
        file_put_contents($componentName->getFusionComponentPath($packagePath), $component->getFusionContent());
        if ($listable) {
            file_put_contents($componentName->getComponentArrayPath($packagePath), $component->getComponentArrayContent());
        }
        $this->registerFactory($package, $componentName);
    }

    /**
     * @param FlowPackageInterface $package
     * @param ComponentName $componentName
     * @return void
     */
    private function registerFactory(FlowPackageInterface $package, ComponentName $componentName): void
    {
        $configurationPath = $package->getPackagePath() . 'Configuration/';
        $configurationFilePath = $configurationPath . 'Settings.PresentationHelpers.yaml';
        if (!file_exists($configurationFilePath)) {
            Files::createDirectoryRecursively($configurationPath);
            $configuration = ['Neos' => ['Fusion' => ['defaultContext' => [
                $componentName->getHelperName() => $componentName->getFullyQualifiedFactoryName()
            ]]]];
        } else {
            $parser = new YamlParser();
            $configuration = $parser->parseFile($configurationFilePath);
            $configuration['Neos']['Fusion']['defaultContext'][$componentName->getHelperName()] = $componentName->getFullyQualifiedFactoryName();
        }

        $writer = new YamlWriter(2);
        file_put_contents($configurationFilePath, $writer->dump($configuration, 100));
    }
}
