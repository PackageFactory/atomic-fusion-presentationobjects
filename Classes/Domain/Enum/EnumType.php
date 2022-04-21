<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

enum EnumType:string
{
    case TYPE_STRING = 'string';
    case TYPE_INT = 'int';

    public function isString(): bool
    {
        return $this === self::TYPE_STRING;
    }

    public function isInt(): bool
    {
        return $this === self::TYPE_INT;
    }

    /**
     * @param array<mixed> $valueArray
     * @return array<mixed>
     */
    public function processValueArray(array $valueArray): array
    {
        $processedValueArray = [];
        switch ($this) {
            case self::TYPE_INT:
                foreach ($valueArray as $value) {
                    list($name, $intValue) = explode(':', $value);
                    $processedValueArray[$name] = (int)$intValue;
                }
                break;
            case self::TYPE_STRING:
                foreach ($valueArray as $value) {
                    $processedValueArray[$value] = $value;
                }
        }

        return $processedValueArray;
    }
}
