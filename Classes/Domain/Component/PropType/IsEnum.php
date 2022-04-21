<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

/**
 * The specification for enum classes
 */
#[Flow\Proxy(false)]
final class IsEnum
{
    public static function isSatisfiedByClassName(string $className): bool
    {
        return class_exists($className)
            && is_subclass_of($className, \BackedEnum::class);
    }
}
