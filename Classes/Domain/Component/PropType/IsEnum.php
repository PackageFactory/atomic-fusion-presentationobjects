<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface;

/**
 * The specification for enum classes
 * @Flow\Proxy(false)
 */
final class IsEnum
{
    public static function isSatisfiedByClassName(string $className): bool
    {
        return class_exists($className)
            && in_array(EnumInterface::class, class_implements($className));
    }
}
