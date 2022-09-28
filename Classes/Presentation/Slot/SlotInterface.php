<?php

/*
* This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
*/

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface SlotInterface extends ComponentPresentationObjectInterface
{
    public function getPrototypeName(): string;
}
