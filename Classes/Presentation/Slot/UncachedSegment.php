<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */
declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class UncachedSegment implements SlotInterface
{
    private string $prototypeName;

    public function __construct(
        string $prototypeName
    ) {
        $this->prototypeName = $prototypeName;
    }

    public function getPrototypeName(): string
    {
        return $this->prototypeName;
    }
}
