<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolverInterface;

/**
 * The enum generator domain service
 *
 * @Flow\Scope("singleton")
 */
final class EnumGenerator
{
    /**
     * @Flow\Inject
     * @var PackageResolverInterface
     */
    protected $packageResolver;

    /**
     * @var \DateTimeImmutable
     */
    protected $now;

    /**
     * @param null|\DateTimeImmutable $now
     */
    public function __construct(?\DateTimeImmutable $now = null)
    {
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
    public function generateEnum(string $componentName, string $name, string $type, array $values, ?string $packageKey = null): void
    {
        $package = $this->packageResolver->resolvePackage($packageKey);

        $enum = new Enum($package->getPackageKey(), $componentName, $name, $type, $values);
        $packagePath = $package->getPackagePath();
        $classPath = $packagePath . 'Classes/Presentation/' . $componentName;
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        file_put_contents($enum->getClassPath($packagePath), $enum->getClassContent());
        file_put_contents($enum->getExceptionPath($packagePath), $enum->getExceptionContent($this->now));

        $dataSourcePath = $packagePath . 'Classes/Application/';
        if (!is_dir($dataSourcePath)) {
            Files::createDirectoryRecursively($dataSourcePath);
        }
        file_put_contents($enum->getProviderPath($packagePath), $enum->getProviderContent());
    }
}
