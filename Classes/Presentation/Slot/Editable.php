<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Editable implements EditableInterface
{
    /**
     * @var TraversableNodeInterface
     */
    private $node;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var boolean
     */
    private $block;

    /**
     * @param TraversableNodeInterface $node
     * @param string $propertyName
     */
    private function __construct(
        TraversableNodeInterface $node,
        string $propertyName,
        bool $block
    ) {
        $this->node = $node;
        $this->propertyName = $propertyName;
        $this->block = $block;
    }

    /**
     * @param TraversableNodeInterface $node
     * @param string $propertyName
     * @param boolean $block
     * @return self
     */
    public static function fromNodeProperty(TraversableNodeInterface $node, string $propertyName, bool $block = true): self
    {
        return new self($node, $propertyName, $block);
    }

    /**
     * @return TraversableNodeInterface
     */
    public function getNode(): TraversableNodeInterface
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return boolean
     */
    public function getIsBlock(): bool
    {
        return $this->block;
    }

    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Editable';
    }
}
