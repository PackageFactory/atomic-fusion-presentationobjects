<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\IntComponentVariant;

/**
 * Dummy int enum for test purposes
 */
enum MyIntEnum: int implements ProtectedContextAwareInterface
{
    use IntComponentVariant;

    case VALUE_ANSWER = 42;
    case VALUE_OTHER = 8472;
}
