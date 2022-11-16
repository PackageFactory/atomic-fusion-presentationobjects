<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

/**
 * Dummy string enum for test purposes
 */
enum MyStringEnum: string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VALUE_MY_VALUE = 'myValue';
    case VALUE_MY_OTHER_VALUE = 'myOtherValue';
}
