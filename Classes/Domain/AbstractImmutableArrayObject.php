<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * The abstract class for immutable array objects
 * @template K
 * @template V
 * @extends \ArrayObject<int|string,V>
 */
abstract class AbstractImmutableArrayObject extends \ArrayObject
{
    /**
     * @param K $key
     * @param V $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    /**
     * @param K $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    /**
     * @param V $value
     * @return void
     */
    public function append($value): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    /**
     * @param array<K,V> $array
     * @return array<K,V>
     */
    public function exchangeArray($array): array
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }
}
