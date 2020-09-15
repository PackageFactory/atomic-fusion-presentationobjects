<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
* This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
*/

interface ValueInterface extends SlotInterface
{
    /**
     * @return string
     */
    public function __toString(): string;
}
