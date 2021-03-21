<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class EnumPropType implements PropTypeInterface
{
    private string $className;

    private bool $nullable;

    public function __construct(
        string $className,
        bool $nullable
    ) {
        $this->className= $className;
        $this->nullable = $nullable;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return \mb_substr($this->className, \mb_strrpos($this->className, '\\') + 1);
    }

    public function getUseStatement(): string
    {
        return "use " . $this->className . ";\n";
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . $this->getSimpleName();
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        $reflection = new \ReflectionClass($this->className);
        try {
            $type = $reflection->getProperty('value')->getType();
        } catch (\ReflectionException $e) {
            return '= \'\'';
        }

        $values = $this->className::getValues();
        $value = reset($values);
        switch ((string) $type) {
            case 'string':
                return '= \'' . $value . '\'';
            case 'int':
            case 'float':
                return '= ' . $value;
            default:
        }

        return '= \'\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }
}
