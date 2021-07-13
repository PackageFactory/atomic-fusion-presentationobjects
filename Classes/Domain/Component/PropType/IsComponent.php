<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * The specification for component classes
 * @Flow\Proxy(false)
 */
final class IsComponent
{
    public static function isSatisfiedByClassName(string $className): bool
    {
        return class_exists($className)
            && is_subclass_of($className, ComponentPresentationObjectInterface::class);
    }

    public static function isSatisfiedByInterfaceName(string $interfaceName): bool
    {
        return interface_exists($interfaceName)
            && is_subclass_of($interfaceName, ComponentPresentationObjectInterface::class);
    }
}
