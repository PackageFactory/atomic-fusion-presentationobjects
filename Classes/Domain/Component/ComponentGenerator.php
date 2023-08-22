<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FactoryRendererInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Dumper as YamlWriter;

/**
 * The component generator domain service
 */
#[Flow\Proxy(false)]
final readonly class ComponentGenerator
{
    public function __construct(
        private FileWriterInterface $fileWriter,
        private FactoryRendererInterface $factoryRenderer
    ) {
    }

    /**
     * @param array|string[] $serializedProps
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
        $component = new Component($componentName, $props);

        $this->fileWriter->writeFile($componentName->getClassPath($packagePath, $colocate), $component->getClassContent());
        $this->fileWriter->writeFile(
            $componentName->getFactoryPath($packagePath, $colocate),
            $this->factoryRenderer->renderFactoryContent($component)
        );
        $this->fileWriter->writeFile($componentName->getFusionComponentPath($packagePath), $component->getFusionContent());

        if ($listable) {
            $this->fileWriter->writeFile($componentName->getComponentArrayPath($packagePath, $colocate), $component->getComponentArrayContent());
        }
        $this->registerFactory($packagePath, $componentName);
    }

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
