<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Value implements SlotInterface, StringLike, \Stringable
{
    private function __construct(
        public string $value
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self($string);
    }

    public static function fromAny(mixed $any): self
    {
        return new self(self::convertToString($any));
    }

    private static function convertToString(mixed $any): string
    {
        if (is_null($any)) {
            return '';
        } elseif (is_scalar($any)) {
            return (string) $any;
        } elseif (is_array($any)) {
            return join('', array_map([self::class, 'convertToString'], $any));
        } elseif (is_callable($any)) {
            return '[callable]';
        } elseif (is_object($any) && method_exists($any, '__toString')) {
            return (string) $any;
        } elseif (is_object($any)) {
            return '[' . get_class($any) . ']';
        } else {
            return sprintf('[unknown type: %s]', gettype($any));
        }
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Value';
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
