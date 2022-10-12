<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class StringLikePropType implements PropTypeInterface
{
    private bool $nullable;

    public function __construct(
        bool $nullable
    ) {
        $this->nullable = $nullable;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return 'stringlike';
    }

    public function getUseStatement(): string
    {
        return 'use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\StringLike;' . PHP_EOL;
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'StringLike';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= \'Text\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.' . $propName . '}'
            . ($this->nullable ? ' @if={presentationObject.' . $propName . '}' : '') . ' />
            ';
    }
}
