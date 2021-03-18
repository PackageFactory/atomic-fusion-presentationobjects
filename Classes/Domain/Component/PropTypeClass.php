<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class PropTypeClass
{
    const CLASS_PRIMITIVE = 'primitive';
    const CLASS_GLOBAL_VALUE = 'globalValue';
    const CLASS_VALUE = 'value';
    const CLASS_COMPONENT = 'component';
    const CLASS_GENERIC = 'generic';

    /**
     * @var string
     */
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function primitive(): self
    {
        return new self(self::CLASS_PRIMITIVE);
    }

    public static function globalValue(): self
    {
        return new self(self::CLASS_GLOBAL_VALUE);
    }

    public static function value(): self
    {
        return new self(self::CLASS_VALUE);
    }

    public static function component(): self
    {
        return new self(self::CLASS_COMPONENT);
    }

    public static function generic(): self
    {
        return new self(self::CLASS_GENERIC);
    }

    public function isPrimitive(): bool
    {
        return $this->value === self::CLASS_PRIMITIVE;
    }

    public function isGlobalValue(): bool
    {
        return $this->value === self::CLASS_GLOBAL_VALUE;
    }

    public function isValue(): bool
    {
        return $this->value === self::CLASS_VALUE;
    }

    public function isComponent(): bool
    {
        return $this->value === self::CLASS_COMPONENT;
    }

    public function isGeneric(): bool
    {
        return $this->value === self::CLASS_GENERIC;
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
