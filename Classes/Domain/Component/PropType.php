<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeIsInvalid;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepository;

/**
 * @Flow\Proxy(false)
 */
final class PropType
{
    const PRIMITIVES = [
        'bool',
        'float',
        'integer',
        'string'
    ];

    /**
     * @var string
     */
    private $fullyQualifiedName;

    /**
     * @var bool
     */
    private $nullable;

    private function __construct(string $fullyQualifiedName, bool $nullable)
    {
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->nullable = $nullable;
    }

    public static function fromType(string $type, PropTypeRepository $propTypeRepository)
    {
        if (!$propTypeRepository->knowsByType($type)) {
            throw PropTypeIsInvalid::becauseItIsNoKnownComponentOrPrimitive($type);
        }

        $values = $propTypeRepository->findValuesByType($type);

        return new self($values['fullyQualifiedName'], $values['isNullable']);
    }

    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }

    public function getSimpleName(): string
    {
        $pivot = \mb_strrpos($this->fullyQualifiedName, '\\');

        return \mb_substr($this->fullyQualifiedName, \mb_strrpos($this->fullyQualifiedName, $pivot ? $pivot + 1 : 0));
    }

    public function isPrimitive(): bool
    {
        return in_array($this->fullyQualifiedName, self::PRIMITIVES);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function toType(): string
    {
        return ($this->isNullable() ? '?' : '') . $this->getSimpleName();
    }

    public function toVar(): string
    {
        return $this->getSimpleName() . ($this->isNullable() ? '|null' : '');
    }
}
