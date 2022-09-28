<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */
declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class CacheSegment implements SlotInterface
{
    public function __construct(
        public readonly SlotInterface $content,
        public readonly string $prototypeName
    ) {
    }

    public function getPrototypeName(): string
    {
        return $this->prototypeName;
    }
}