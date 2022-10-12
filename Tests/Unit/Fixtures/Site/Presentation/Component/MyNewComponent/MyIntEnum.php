<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

/**
 * Dummy int enum for test purposes
 */
enum MyIntEnum: int
{
    case VALUE_ANSWER = 42;
    case VALUE_OTHER = 8472;
}
