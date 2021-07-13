<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
* This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
*/

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface SlotInterface extends ComponentPresentationObjectInterface
{
    /**
     * @return string
     */
    public function getPrototypeName(): string;
}
