<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;

#[Flow\Proxy(false)]
final class EnumName
{
    public function __construct(
        public readonly ComponentName $componentName,
        public readonly string $name
    ) {
    }

    public function getPackageKey(): PackageKey
    {
        return $this->componentName->packageKey;
    }

    public function getFusionNamespace(): FusionNamespace
    {
        return $this->componentName->fusionNamespace;
    }

    public function getPhpNamespace(): string
    {
        return $this->componentName->getPhpNamespace();
    }

    /**
     * @return class-string<mixed>
     */
    public function getPhpClassName(): string
    {
        /** @var class-string<mixed> $name */
        $name = $this->componentName->getPhpNamespace() . '\\' . $this->name;

        return $name;
    }

    /**
     * @return class-string<mixed>
     */
    public function getProviderName(): string
    {
        /** @var class-string<mixed> $name */
        $name = $this->name . 'Provider';

        return $name;
    }

    public function getPhpFilePath(string $packagePath, bool $colocate): string
    {
        return $this->componentName->getPhpFilePath($packagePath, $colocate);
    }

    public function getClassPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/' . $this->name . '.php';
    }
}
