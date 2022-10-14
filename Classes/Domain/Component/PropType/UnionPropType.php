<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class UnionPropType implements PropTypeInterface
{
    /**
     * @var array<int,PropTypeInterface>
     */
    public readonly array $propTypes;

    public function __construct(
        private readonly bool $nullable,
        PropTypeInterface ...$propTypes
    ) {
        $this->propTypes = $propTypes;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        // not to be used, a special case should be used evaluating the child prop types
        return 'union';
    }

    public function getUseStatement(): string
    {
        // not to be used, a special case should be used evaluating the child prop types
        return '';
    }

    public function getType(): string
    {
        return implode(
            '|',
            array_map(
                fn (PropTypeInterface $propType): string => $propType->getType(),
                $this->propTypes
            )
        ) . ($this->isNullable() ? '|null' : '');
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= \'\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.' . $propName . '}'
            . ($this->nullable ? ' @if={presentationObject.' . $propName . '}' : '') . ' />
            ';
    }
}
