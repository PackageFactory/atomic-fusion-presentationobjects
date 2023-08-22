<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Headline;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

/**
 * HeadlineLook enum for test purposes
 */
enum HeadlineLook: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case LOOK_LARGE = 'large';
}
