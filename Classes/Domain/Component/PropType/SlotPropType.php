<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class SlotPropType implements PropTypeInterface
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
        return 'slot';
    }

    public function getUseStatement(): string
    {
        return 'use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;' . PHP_EOL;
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'SlotInterface';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= \'- Add Slot Content -\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '
            <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.' . $propName . '} />
        ';
    }
}
