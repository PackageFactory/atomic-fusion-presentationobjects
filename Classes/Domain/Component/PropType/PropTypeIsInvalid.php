<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if an invalid prop type was attempted to be used
 */
#[Flow\Proxy(false)]
final class PropTypeIsInvalid extends \InvalidArgumentException
{
    public static function becauseItIsNoKnownComponentValueOrPrimitive(string $attemptedType): self
    {
        return new self('Given prop type "' . $attemptedType . '" is invalid. It must be either a primitive or a known sub component.', 1582385578);
    }

    public static function becausePropertyIsNotTyped(\ReflectionProperty $property): self
    {
        return new self('Given property "' . $property->getName() . '" is not typed.', 1616365306);
    }
}
