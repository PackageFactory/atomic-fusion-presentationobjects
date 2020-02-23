<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The repository for all supported prop types
 *
 * @Flow\Scope("singleton")
 */
final class PropTypeRepository
{
    /**
     * @var array
     */
    private $primitives = [
        'string' => true,
        'int' => true,
        'float' => true,
        'bool' => true
    ];

    public function findByType(string $type): ?PropType
    {
        if (!$this->knowsByType($type)) {
            return null;
        }

        return PropType::fromType($type, $this);
    }

    public function findValuesByType(string $type): ?array
    {
        if (!$this->knowsByType($type)) {
            return null;
        }

        $fullyQualifiedName = $type;
        $isNullable = false;
        if (\mb_strpos($type, '?') === 0) {
            $isNullable = true;
            $fullyQualifiedName = \mb_substr($fullyQualifiedName, 1);
        }

        return [
            'fullyQualifiedName' => $fullyQualifiedName,
            'isNullable' => $isNullable
        ];
    }

    public function knowsByType(string $type): bool
    {
        $type = trim($type, '?');

        return isset($this->primitives[$type]);
    }

    public function findSupportedPropTypes(): array
    {
        return array_keys($this->primitives);
    }
}
