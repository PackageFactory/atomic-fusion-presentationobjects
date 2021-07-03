<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class EnumLabel
{
    private string $labelIdPrefix;

    private string $sourceName;

    private string $packageKey;

    private function __construct(
        string $labelIdPrefix,
        string $sourceName,
        string $packageKey
    ) {
        $this->labelIdPrefix = $labelIdPrefix;
        $this->sourceName = $sourceName;
        $this->packageKey = $packageKey;
    }

    public static function fromEnumName(string $enumName): self
    {
        list($packageKey, $componentName) = explode('/Presentation/', $enumName);
        $pivot = \mb_strrpos($componentName, '/');
        $componentNamespace = \mb_substr($packageKey, 0 , $pivot);
        $enumShort = lcfirst(\mb_substr($packageKey, $pivot+1));

        return new self(
            $enumShort . '.',
            \str_replace('/', '.', $componentNamespace),
            \str_replace('/', '.', $packageKey)
        );
    }

    public function getLabelIdPrefix(): string
    {
        return $this->labelIdPrefix;
    }

    public function getSourceName(): string
    {
        return $this->sourceName;
    }

    public function getPackageKey(): string
    {
        return $this->packageKey;
    }
}
