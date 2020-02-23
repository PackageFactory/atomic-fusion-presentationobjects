<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Value;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\PackageManager;
use Neos\Utility\Files;

/**
 * The value generator domain service
 *
 * @Flow\Scope("singleton")
 */
final class ValueGenerator
{
    /**
     * @Flow\Inject
     * @var PackageManager
     */
    protected $packageManager;

    public function generateValue(string $packageKey, string $componentName, string $name, string $type, array $values, bool $generateDataSource): void
    {
        $value = new Value($packageKey, $componentName, $name, $type, $values);

        $packagePath = $this->packageManager->getPackage($packageKey)->getPackagePath();
        $classPath = $packagePath . 'Classes/Presentation/' . $componentName;
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        file_put_contents($value->getClassPath($packagePath), $value->getClassContent());
        file_put_contents($value->getExceptionPath($packagePath), $value->getExceptionContent());

        if ($generateDataSource) {
            $dataSourcePath = $packagePath . 'Classes/Application/';
            if (!is_dir($dataSourcePath)) {
                Files::createDirectoryRecursively($dataSourcePath);
            }
            file_put_contents($value->getDataSourcePath($packagePath), $value->getDataSourceContent());
        }
    }
}
