<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;

/**
 * @Flow\Proxy(false)
 */
final class PackageKey
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromPackage(FlowPackageInterface $package): self
    {
        return new self($package->getPackageKey());
    }

    public static function fromPhpNamespace(string $namespace): self
    {
        return new self(\str_replace('\\', '.', $namespace));
    }

    public function toFusionNamespace(): string
    {
        return $this->value . ':';
    }

    public function toPhpNamespace(): string
    {
        return \str_replace('.', '\\', $this->value);
    }

    public function getSimpleName(): string
    {
        return \mb_substr($this->value, \mb_strrpos($this->value, '.') + 1);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
