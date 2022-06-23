<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class EnumPropType implements PropTypeInterface
{
    /**
     * @var class-string<mixed>
     */
    private string $className;

    private bool $nullable;

    /**
     * @param class-string<mixed> $className
     * @param boolean $nullable
     */
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

        $cases = $this->className::cases();
        if (!empty($cases)) {
            /** @var PseudoEnumInterface $defaultCase */
            $defaultCase = reset($cases);
            switch ((string) $type) {
                case 'string':
                    return '= \'' . $defaultCase->getValue() . '\'';
                case 'int':
                    return '= ' . $defaultCase->getValue();
                default:
            }
        }

        return '= \'\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '}';
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
