<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PluralName;

/**
 * @Flow\Proxy(false)
 */
final class ComponentArrayPropType implements PropTypeInterface
{
    private ComponentName $componentName;

    public function __construct(
        ComponentName $componentName
    ) {
        $this->componentName = $componentName;
    }

    public function isNullable(): bool
    {
        return false;
    }

    public function getSimpleName(): string
    {
        return PluralName::forName($this->componentName->name);
    }

    public function getUseStatement(): string
    {
        return 'use ' . $this->componentName->getFullyQualifiedComponentArrayName() . ';
';
    }

    public function getType(): string
    {
        return $this->getSimpleName();
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        $componentPropType = new ComponentPropType($this->componentName, false);
        $componentStyleGuideValue = $componentPropType->getStyleGuideValue($nestingLevel + 1);
        return '{
' . self::innerLeftPad($nestingLevel) . '0 ' . $componentStyleGuideValue . '
' . self::innerLeftPad($nestingLevel) . '1 ' .$componentStyleGuideValue . '
' . self::outerLeftPad($nestingLevel) . '}';
    }

    private static function innerLeftPad(int $nestingLevel): string
    {
        return '            ' . str_repeat(' ', $nestingLevel * 4);
    }

    private static function outerLeftPad(int $nestingLevel): string
    {
        return '        ' . str_repeat(' ', $nestingLevel * 4);
    }

    public function getDefinitionData(string $propName): string
    {
        return '
                <Neos.Fusion:Loop items={presentationObject.' . $propName. '}>
                    <' . $this->componentName->getFullyQualifiedFusionName() . ' presentationObject={item} />
                </Neos.Fusion:Loop>
            ';
    }
}
