<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Value implements ValueInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $string
     * @return self
     */
    public static function fromString(string $string): self
    {
        return new self($string);
    }

    /**
     * @param mixed $any
     * @return self
     */
    public static function fromAny($any): self
    {
        return new self(self::convertToString($any));
    }

    /**
     * @param mixed $any
     * @return string
     */
    private static function convertToString($any): string
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

    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Value';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
