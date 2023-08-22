<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */
declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class UncachedSegment implements SlotInterface
{
    public function __construct(
        private string $prototypeName
    ) {
    }

    public function getPrototypeName(): string
    {
        return $this->prototypeName;
    }
}
