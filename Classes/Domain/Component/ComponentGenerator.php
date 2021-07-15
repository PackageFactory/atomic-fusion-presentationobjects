<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Dumper as YamlWriter;

/**
 * The component generator domain service
 *
 * @Flow\Proxy(false)
 */
final class ComponentGenerator
{
    private FileWriterInterface $fileWriter;

    public function __construct(FileWriterInterface $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    /**
     * @param ComponentName $componentName
     * @param array|string[] $serializedProps
     * @param string $packagePath
     * @param bool $colocate
     * @param bool $listable
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function generateComponent(
        ComponentName $componentName,
        array $serializedProps,
        string $packagePath,
        bool $colocate,
        bool $listable = false
    ): void {
        $props = Props::fromInputArray($componentName, $serializedProps);
        $component = new Component($componentName, $props, $listable);

        $this->fileWriter->writeFile($componentName->getInterfacePath($packagePath, $colocate), $component->getInterfaceContent());
        $this->fileWriter->writeFile($componentName->getClassPath($packagePath, $colocate), $component->getClassContent());
        $this->fileWriter->writeFile($componentName->getFactoryPath($packagePath, $colocate), $component->getFactoryContent());
        $this->fileWriter->writeFile($componentName->getFusionComponentPath($packagePath), $component->getFusionContent());

        if ($listable) {
            $this->fileWriter->writeFile($componentName->getComponentArrayPath($packagePath, $colocate), $component->getComponentArrayContent());
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
            $configuration = ['Neos' => ['Fusion' => ['defaultContext' => [
                $componentName->getHelperName() => $componentName->getFullyQualifiedFactoryName()
            ]]]];
        } else {
            $parser = new YamlParser();
            $configuration = $parser->parseFile($configurationFilePath);
            $configuration['Neos']['Fusion']['defaultContext'][$componentName->getHelperName()] = $componentName->getFullyQualifiedFactoryName();
        }

        $writer = new YamlWriter(2);
        $this->fileWriter->writeFile($configurationFilePath, $writer->dump($configuration, 100));
    }
}
