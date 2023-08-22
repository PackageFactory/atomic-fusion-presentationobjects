<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * The generic abstract component presentation object implementation
 */
abstract readonly class AbstractComponentPresentationObject implements ComponentPresentationObjectInterface, SlotInterface
{
    public function getPrototypeName(): string
    {
        return ComponentName::fromClassName(static::class)
            ->getFullyQualifiedFusionName();
    }
}
