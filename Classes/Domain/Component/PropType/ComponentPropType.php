<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Props;

/**
 * @Flow\Proxy(false)
 */
final class ComponentPropType implements PropTypeInterface
{
    private ComponentName $componentName;

    private bool $nullable;

    public function __construct(
        ComponentName $componentName,
        bool $nullable
    ) {
        $this->componentName = $componentName;
        $this->nullable = $nullable;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return $this->componentName->getSimpleClassName();
    }

    public function getUseStatement(): string
    {
        return 'use ' . $this->componentName->getFullyQualifiedClassName() . ';
';
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . $this->componentName->getSimpleClassName();
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        $styleGuideValue = "{\n";
        $props = Props::fromClassName($this->componentName->getFullyQualifiedClassName());
        $styleGuideValue .= $props->renderStyleGuideProps($nestingLevel);
        $styleGuideValue .= '
' . self::leftPad($nestingLevel) . '}';

        return $styleGuideValue;
    }

    private static function leftPad(int $nestingLevel): string
    {
        return '        ' . \str_repeat(' ', $nestingLevel * 4);
    }

    public function getDefinitionData(string $propName): string
    {
        return '
                <' . $this->componentName->getFullyQualifiedFusionName() . ' presentationObject={presentationObject.' . $propName . '}' . ($this->isNullable() ? ' @if={presentationObject.' . $propName . '}' : '') . ' />
            ';
    }
}
