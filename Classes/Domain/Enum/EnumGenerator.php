<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;

/**
 * The enum generator domain service
 *
 * @Flow\Scope("singleton")
 */
final class EnumGenerator
{
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
     * @param ComponentName $componentName
     * @param string $name
     * @param string $type
     * @param array|string[] $values
     * @param string $packagePath
     * @return void
     */
    public function generateEnum(
        ComponentName $componentName,
        string $name,
        string $type,
        array $values,
        string $packagePath
    ): void {
        $enumType = EnumType::fromInput($type);
        $enumName = new EnumName(
            $componentName,
            $name
        );
        $enum = new Enum($enumName, $enumType, $enumType->processValueArray($values));

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
