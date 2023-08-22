<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class EnumPropType implements PropTypeInterface
{
    public function __construct(
        /** @var class-string<\BackedEnum> */
        public string $className,
        private bool $nullable
    ) {
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
        return '';
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
            /** @var \BackedEnum $defaultCase */
            $defaultCase = reset($cases);
            switch ((string) $type) {
                case 'string':
                    return '= \'' . $defaultCase->value . '\'';
                case 'int':
                    return '= ' . $defaultCase->value;
                default:
            }
        }

        return '= \'\'';
    }

    public function getDefinitionData(string $propName): string
    {
        return '{presentationObject.' . $propName . '.value}';
    }
}
