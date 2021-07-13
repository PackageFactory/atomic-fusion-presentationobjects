<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class ComponentType
{
    const TYPE_LEAF = 'leaf';
    const TYPE_COMPOSITE = 'composite';

    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function leaf(): self
    {
        return new self(self::TYPE_LEAF);
    }

    public static function composite(): self
    {
        return new self(self::TYPE_COMPOSITE);
    }

    public function isLeaf(): bool
    {
        return $this->value === self::TYPE_LEAF;
    }

    public function isComposite(): bool
    {
        return $this->value === self::TYPE_COMPOSITE;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
