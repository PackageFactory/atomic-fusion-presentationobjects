<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

#[Flow\Proxy(false)]
final class Content implements SlotInterface
{
    private function __construct(
        public readonly TraversableNodeInterface $contentNode,
        public readonly string $contentPrototypeName
    ) {
    }

    public static function fromNode(TraversableNodeInterface $node, ?string $contentPrototypeName = null): self
    {
        return new self($node, $contentPrototypeName ?? $node->getNodeType()->getName());
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Content';
    }
}
