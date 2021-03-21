<?php

namespace Vendor\Site\Presentation\Component\Text;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * Text interface for test purposes
 */
interface TextInterface extends ComponentPresentationObjectInterface
{
    public function getText(): string;
}
