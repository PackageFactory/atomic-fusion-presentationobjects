<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * An exception to be thrown if a component's presentation object does not implement the required interface
 *
 * @Flow\Proxy(false)
 */
class ComponentPresentationObjectDoesNotImplementRequiredInterface extends \DomainException
{
    public static function butWasSupposedTo(string $requiredInterface): self
    {
        return new self('Presentation object does not implement required ' . $requiredInterface . '.', 1616076981);
    }
}
