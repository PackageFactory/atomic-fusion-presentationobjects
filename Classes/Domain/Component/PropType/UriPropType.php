<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class UriPropType implements PropTypeInterface
{
    public function __construct(
        private readonly bool $nullable
    ) {
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return 'UriInterface';
    }

    public function getUseStatement(): string
    {
        return "use " . UriInterface::class . ";\n";
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'UriInterface';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= \'https://www.neos.io\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }
}
