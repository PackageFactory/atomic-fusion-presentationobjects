<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

/**
 * Dummy string enum for test purposes
 */
enum MyStringEnum:string
{
    case VALUE_MY_VALUE = 'myValue';
    case VALUE_MY_OTHER_VALUE = 'myOtherValue';
}
