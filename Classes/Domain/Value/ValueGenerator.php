<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Value;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolver;

/**
 * The value generator domain service
 *
 * @deprecated 2.0
 * @Flow\Scope("singleton")
 */
final class ValueGenerator
{
    /**
     * @Flow\Inject
     * @var PackageResolver
     */
    protected $packageResolver;

    public function generateValue(string $componentName, string $name, string $type, array $values, ?string $packageKey = null): void
    {
        $package = $this->packageResolver->resolvePackage($packageKey);

        $value = new Value($package->getPackageKey(), $componentName, $name, $type, $values);
        $packagePath = $package->getPackagePath();
        $classPath = $packagePath . 'Classes/Presentation/' . $componentName;
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        file_put_contents($value->getClassPath($packagePath), $value->getClassContent());
        file_put_contents($value->getExceptionPath($packagePath), $value->getExceptionContent());

        $dataSourcePath = $packagePath . 'Classes/Application/';
        if (!is_dir($dataSourcePath)) {
            Files::createDirectoryRecursively($dataSourcePath);
        }
        file_put_contents($value->getProviderPath($packagePath), $value->getProviderContent());
    }
}
