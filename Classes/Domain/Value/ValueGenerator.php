<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Value;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;

/**
 * The value generator domain service
 *
 * @Flow\Scope("singleton")
 */
final class ValueGenerator
{
    /**
     * @var PackageResolverInterface
     */
    protected PackageResolverInterface $packageResolver;

    /**
     * @var \DateTimeImmutable
     */
    protected \DateTimeImmutable $now;

    /**
     * @param PackageResolverInterface $packageResolver
     * @param null|\DateTimeImmutable $now
     */
    public function __construct(PackageResolverInterface $packageResolver, ?\DateTimeImmutable $now = null)
    {
        $this->packageResolver = $packageResolver;
        $this->now = $now ?? new \DateTimeImmutable();
    }

    /**
     * @param string $componentName
     * @param string $name
     * @param string $type
     * @param array|string[] $values
     * @param null|string $packageKey
     * @return void
     */
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
        file_put_contents($value->getExceptionPath($packagePath), $value->getExceptionContent($this->now));

        $dataSourcePath = $packagePath . 'Classes/Application/';
        if (!is_dir($dataSourcePath)) {
            Files::createDirectoryRecursively($dataSourcePath);
        }
        file_put_contents($value->getProviderPath($packagePath), $value->getProviderContent());
    }
}
