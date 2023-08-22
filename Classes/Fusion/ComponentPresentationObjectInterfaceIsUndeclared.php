<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use Neos\Flow\Annotations as Flow;

/**
 * An exception to be thrown if a presentation object component has no interface declared
 */
#[Flow\Proxy(false)]
final class ComponentPresentationObjectInterfaceIsUndeclared extends \DomainException
{
    public static function butWasSupposedTo(): self
    {
        return new self(
            'The component\'s presentation object interface is undeclared, set it via @presentationObjectInterface = \'...\'.',
            1616077232
        );
    }
}
