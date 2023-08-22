<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;

/**
 * The specification for component array classes
 */
#[Flow\Proxy(false)]
final class IsComponentArray
{
    public static function isSatisfiedByInputString(string $input): bool
    {
        return \mb_strpos($input, 'array<') === 0 && \mb_substr($input, -1, 1) === '>';
    }

    public static function isSatisfiedByClassName(string $className): bool
    {
        return class_exists($className)
            && is_subclass_of($className, \Traversable::class);
    }
}
