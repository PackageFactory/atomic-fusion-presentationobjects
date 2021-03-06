<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;

/**
 * @Flow\Proxy(false)
 */
final class EnumLabel
{
    private string $labelIdPrefix;

    private string $sourceName;

    private string $packageKey;

    public function __construct(
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
        list($packageNamespace, $componentName) = explode('\Presentation\\', $enumName);
        $pivot = \mb_strrpos($componentName, '\\') ?: null;
        $componentNamespace = \mb_substr($componentName, 0, $pivot);
        $enumShort = lcfirst(\mb_substr($componentName, $pivot+1));

        return new self(
            $enumShort . '.',
            \str_replace('\\', '.', $componentNamespace),
            \str_replace('\\', '.', $packageNamespace)
        );
    }

    public function translate(string $value, Translator $translator): string
    {
        return $translator->translateById(
            $this->labelIdPrefix . $value,
            [],
            null,
            null,
            $this->sourceName,
            $this->packageKey
        ) ?: $value;
    }
}
