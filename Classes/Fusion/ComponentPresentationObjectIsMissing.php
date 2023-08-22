<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/**
 * An exception to be thrown if a component's presentation object is missing
 */
class ComponentPresentationObjectIsMissing extends \DomainException
{
    public static function butMustNotBe(): self
    {
        return new self('Component presentation object is missing, set it via presentationObject = ... .', 1616077282);
    }
}
