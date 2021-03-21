<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PluralName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;

/**
 * @Flow\Proxy(false)
 */
final class EnumName
{
    private PackageKey $packageKey;

    private FusionNamespace $fusionNamespace;

    private string $componentName;

    private string $name;

    public function __construct(
        PackageKey $packageKey,
        FusionNamespace $fusionNamespace,
        string $componentName,
        string $name
    ) {
        $this->packageKey = $packageKey;
        $this->fusionNamespace = $fusionNamespace;
        $this->componentName = $componentName;
        $this->name = $name;
    }

    public function getPackageKey(): PackageKey
    {
        return $this->packageKey;
    }

    public function getFusionNamespace(): FusionNamespace
    {
        return $this->fusionNamespace;
    }

    public function getComponentName(): string
    {
        return $this->componentName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): string
    {
        return $this->packageKey->toPhpNamespace() . '\Presentation\\' . $this->fusionNamespace->toPhpNameSpace() . '\\' . $this->componentName;
    }

    public function getFullyQualifiedName(): string
    {
        return $this->getNamespace() . '\\' . $this->name;
    }

    public function getExceptionName(): string
    {
        return $this->name . 'IsInvalid';
    }

    public function getProviderName(): string
    {
        return $this->name . 'Provider';
    }

    public function getPhpFilePath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->fusionNamespace->toFilePath() . '/' . $this->componentName;
    }

    public function getClassPath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/' . $this->name . '.php';
    }

    public function getExceptionPath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/' . $this->getExceptionName() . '.php';
    }

    public function getProviderBasePath(string $packagePath): string
    {
        return $packagePath . '/Classes/Application';
    }

    public function getProviderPath(string $packagePath): string
    {
        return $this->getProviderBasePath($packagePath) . '/' . $this->name . 'Provider.php';
    }

    public function getProviderNamespace(): string
    {
        return $this->packageKey->toPhpNamespace() . '\\Application';
    }

    public function getDataSourceIdentifier(): string
    {
        return strtolower(str_replace('.', '-', (string)$this->packageKey) . '-' .  implode('-', $this->splitName()));
    }

    /**
     * @return string[]
     */
    private function splitName(): array
    {
        $nameParts = [];
        $parts = preg_split("/([A-Z])/", PluralName::forName($this->name), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (is_array($parts)) {
            foreach ($parts as $i => $part) {
                if ($i % 2 === 0) {
                    $nameParts[$i / 2] = $part;
                } else {
                    $nameParts[($i - 1) / 2] .= $part;
                }
            }
        }

        return $nameParts;
    }
}
