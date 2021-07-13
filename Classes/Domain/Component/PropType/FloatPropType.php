<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class FloatPropType implements PropTypeInterface
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
        return 'float';
    }

    public function getUseStatement(): string
    {
        return '';
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'float';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= 47.11';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }
}
