<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Content implements SlotInterface
{
    private function __construct(
        public Node $contentNode,
        public string $contentPrototypeName
    ) {
    }

    public static function fromNode(Node $node, ?string $contentPrototypeName = null): self
    {
        return new self($node, $contentPrototypeName ?? $node->nodeTypeName->value);
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Content';
    }
}
