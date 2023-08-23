<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/**
 * A trait for int component variants for use in EEL
 */
trait IntComponentVariant
{
    public function equals(int $other): bool
    {
        return $this === self::from($other);
    }

    /**
     * @param string $methodName
     */
    public function allowsCallOfMethod($methodName): true
    {
        return true;
    }
}
