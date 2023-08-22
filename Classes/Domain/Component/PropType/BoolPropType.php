<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class BoolPropType implements PropTypeInterface
{
    public function __construct(
        private bool $nullable
    ) {
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return 'bool';
    }

    public function getUseStatement(): string
    {
        return '';
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'bool';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= true';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }
}
