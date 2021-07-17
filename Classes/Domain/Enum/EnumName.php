<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;

/**
 * @Flow\Proxy(false)
 */
final class EnumName
{
    private ComponentName $componentName;

    private string $name;

    public function __construct(
        ComponentName $componentName,
        string $name
    ) {
        $this->componentName = $componentName;
        $this->name = $name;
    }

    public function getPackageKey(): PackageKey
    {
        return $this->componentName->getPackageKey();
    }

    public function getFusionNamespace(): FusionNamespace
    {
        return $this->componentName->getFusionNamespace();
    }

    public function getComponentName(): ComponentName
    {
        return $this->componentName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhpNamespace(): string
    {
        return $this->componentName->getPhpNamespace();
    }

    public function getExceptionName(): string
    {
        return $this->name . 'IsInvalid';
    }

    public function getProviderName(): string
    {
        return $this->name . 'Provider';
    }

    public function getPhpFilePath(string $packagePath, bool $colocate): string
    {
        return $this->componentName->getPhpFilePath($packagePath, $colocate);
    }

    public function getClassPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/' . $this->name . '.php';
    }

    public function getExceptionPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/' . $this->getExceptionName() . '.php';
    }
}
