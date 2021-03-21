<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class IntPropType implements PropTypeInterface
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
        return 'int';
    }

    public function getUseStatement(): string
    {
        return '';
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'int';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= 4711';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }
}
