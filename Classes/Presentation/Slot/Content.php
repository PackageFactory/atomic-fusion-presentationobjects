<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

/**
 * @Flow\Proxy(false)
 */
final class Content implements ContentInterface
{
    /**
     * @var TraversableNodeInterface
     */
    private $contentNode;

    /**
     * @var string
     */
    private $contentPrototypeName;

    /**
     * @param TraversableNodeInterface $contentNode
     * @param string $contentPrototypeName
     */
    private function __construct(TraversableNodeInterface $contentNode, string $contentPrototypeName)
    {
        $this->contentNode = $contentNode;
        $this->contentPrototypeName = $contentPrototypeName;
    }

    /**
     * @param TraversableNodeInterface $node
     * @param null|string $contentPrototypeName
     * @return self
     */
    public static function fromNode(TraversableNodeInterface $node, ?string $contentPrototypeName = null): self
    {
        return new self($node, $contentPrototypeName ?? $node->getNodeType()->getName());
    }

    /**
     * @return TraversableNodeInterface
     */
    public function getContentNode(): TraversableNodeInterface
    {
        return $this->contentNode;
    }

    /**
     * @return string
     */
    public function getContentPrototypeName(): string
    {
        return $this->contentPrototypeName;
    }

    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Content';
    }
}
