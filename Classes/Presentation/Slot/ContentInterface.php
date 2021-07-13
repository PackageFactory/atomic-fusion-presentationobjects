<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
* This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
*/

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

interface ContentInterface extends SlotInterface
{
    /**
     * @return TraversableNodeInterface
     */
    public function getContentNode(): TraversableNodeInterface;

    /**
     * @return string
     */
    public function getContentPrototypeName(): string;
}
