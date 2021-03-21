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
            && in_array(ComponentPresentationObjectInterface::class, class_implements($className));
    }

    public static function isSatisfiedByInterfaceName(string $interfaceName): bool
    {
        return interface_exists($interfaceName)
            && in_array(ComponentPresentationObjectInterface::class, class_implements($interfaceName));
    }
}
