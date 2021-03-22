<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
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
     * @param ComponentName $componentName
     * @param array|string[] $serializedProps
     * @param string $packagePath
     * @param bool $listable
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generateComponent(
        ComponentName $componentName,
        array $serializedProps,
        string $packagePath,
        bool $listable = false
    ): void {
        $props = Props::fromInputArray($componentName->getPackageKey(), $componentName->getName(), $serializedProps);
        $component = new Component($componentName, $props, $listable);

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
        $this->registerFactory($packagePath, $componentName);
    }

    /**
     * @param string $packagePath
     * @param ComponentName $componentName
     * @return void
     */
    private function registerFactory(string $packagePath, ComponentName $componentName): void
    {
        $configurationPath = $packagePath . 'Configuration/';
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
