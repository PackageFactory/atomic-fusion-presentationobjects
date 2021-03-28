<?php

namespace Vendor\Shared\Presentation\Custom\Type\Text;

/*
 * This file is part of the Vendor.Shared package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * Text interface for test purposes
 */
interface TextInterface extends ComponentPresentationObjectInterface
{
    public function getText(): string;
}
