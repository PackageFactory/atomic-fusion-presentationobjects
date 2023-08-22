<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class Editable implements SlotInterface, StringLike
{
    private function __construct(
        public readonly TraversableNodeInterface $node,
        public readonly string $propertyName,
        public readonly bool $isBlock
    ) {
    }

    public static function fromNodeProperty(TraversableNodeInterface $node, string $propertyName, bool $block = true): self
    {
        return new self($node, $propertyName, $block);
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Editable';
    }
}
