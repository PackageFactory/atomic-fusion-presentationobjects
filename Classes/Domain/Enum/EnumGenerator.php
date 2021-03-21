<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
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
    public function generateEnum(string $componentName, string $name, string $type, array $values, ?string $packageKey = null, ?FusionNamespace $namespace = null): void
    {
        $enumType = EnumType::fromInput($type);
        $package = $this->packageResolver->resolvePackage($packageKey);
        $enumName = new EnumName(
            PackageKey::fromPackage($package),
            $namespace ?: FusionNamespace::default(),
            $componentName,
            $name
        );
        $enum = new Enum($enumName, $enumType, $enumType->processValueArray($values));

        $packagePath = $package->getPackagePath();
        $classPath = $enumName->getPhpFilePath($packagePath);
        if (!file_exists($classPath)) {
            Files::createDirectoryRecursively($classPath);
        }
        file_put_contents($enumName->getClassPath($packagePath), $enum->getClassContent());
        file_put_contents($enumName->getExceptionPath($packagePath), $enum->getExceptionContent($this->now));

        $providerBasePath = $enumName->getProviderBasePath($packagePath);
        if (!is_dir($providerBasePath)) {
            Files::createDirectoryRecursively($providerBasePath);
        }
        file_put_contents($enumName->getProviderPath($packagePath), $enum->getProviderContent());
    }
}
