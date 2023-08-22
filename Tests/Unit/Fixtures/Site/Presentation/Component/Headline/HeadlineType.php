<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Headline;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

/**
 * HeadlineType enum for test purposes
 */
enum HeadlineType: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case TYPE_H1 = 'h1';
}
