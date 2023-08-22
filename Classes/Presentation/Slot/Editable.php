<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Editable implements SlotInterface, StringLike
{
    private function __construct(
        public Node $node,
        public string $propertyName,
        public bool $isBlock
    ) {
    }

    public static function fromNodeProperty(Node $node, string $propertyName, bool $block = true): self
    {
        return new self($node, $propertyName, $block);
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Editable';
    }
}
