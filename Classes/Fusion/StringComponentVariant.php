<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/**
 * A trait for string component variants for use in EEL
 */
trait StringComponentVariant
{
    public function equals(string $other): bool
    {
        return $this === self::from($other);
    }

    /**
     * @param string $methodName
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
