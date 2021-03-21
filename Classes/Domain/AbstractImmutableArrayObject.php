<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The abstract class for immutable array objects
 */
abstract class AbstractImmutableArrayObject extends \ArrayObject
{
    public function offsetSet($key, $value): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    public function offsetUnset($key): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    public function append($value): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }

    public function exchangeArray($array): void
    {
        throw new \BadMethodCallException(get_class() . ' are immutable.', 1616240304);
    }
}
