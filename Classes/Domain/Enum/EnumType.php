<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class EnumType
{
    const TYPE_STRING = 'string';
    const TYPE_INT = 'int';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromInput(string $input): self
    {
        if ($input !== self::TYPE_STRING && $input !== self::TYPE_INT) {
            throw EnumTypeIsInvalid::becauseItIsNoneOfTheSupportedTypes($input);
        }

        return new self($input);
    }

    public static function string(): self
    {
        return new self(self::TYPE_STRING);
    }

    public static function int(): self
    {
        return new self(self::TYPE_INT);
    }

    public function isString(): bool
    {
        return $this->value === self::TYPE_STRING;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param array<mixed> $valueArray
     * @return array<mixed>
     */
    public function processValueArray(array $valueArray): array
    {
        $processedValueArray = [];
        switch ($this->value) {
            case self::TYPE_INT:
                foreach ($valueArray as $value) {
                    list($name, $intValue) = explode(':', $value);
                    $processedValueArray[$name] = (int)$intValue;
                }
                break;
            case self::TYPE_STRING:
            default:
                foreach ($valueArray as $value) {
                    $processedValueArray[$value] = $value;
                }
        }

        return $processedValueArray;
    }
}
