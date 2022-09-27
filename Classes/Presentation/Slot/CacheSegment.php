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
final class CacheSegment implements CacheSegmentInterface
{
    private SlotInterface $content;

    private string $prototypeName;

    public function __construct(
        SlotInterface $content,
        string $prototypeName
    ) {
        $this->content = $content;
        $this->prototypeName = $prototypeName;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getPrototypeName(): string
    {
        return $this->prototypeName;
    }
}
