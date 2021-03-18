<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if an invalid prop type was attempted to be used
 *
 * @Flow\Proxy(false)
 */
final class PropTypeIsInvalid extends \InvalidArgumentException
{
    public static function becauseItIsNoKnownComponentValueOrPrimitive(string $attemptedType): self
    {
        return new self('Given prop type "' . $attemptedType . '" is invalid. It must be either a primitive or a known sub component.', 1582385578);
    }
}
