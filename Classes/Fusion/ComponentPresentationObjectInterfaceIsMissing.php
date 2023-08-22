<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/**
 * An exception to be thrown if a component's declared presentation object interface is missing
 */
class ComponentPresentationObjectInterfaceIsMissing extends \DomainException
{
    public static function butWasNotSupposedTo(string $interfaceName): self
    {
        return new self(
            'Declared presentation object interface "' . $interfaceName
            . '" is missing, please add it to your codebase.',
            1616077140
        );
    }
}
