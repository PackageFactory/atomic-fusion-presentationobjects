<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
* This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
*/

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

interface EditableInterface extends SlotInterface
{
    /**
     * @return TraversableNodeInterface
     */
    public function getNode(): TraversableNodeInterface;

    /**
     * @return string
     */
    public function getPropertyName(): string;

    /**
     * @return boolean
     */
    public function getIsBlock(): bool;
}
