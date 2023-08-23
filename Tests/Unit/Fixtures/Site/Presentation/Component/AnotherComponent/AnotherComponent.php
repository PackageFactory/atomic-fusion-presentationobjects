<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\AnotherComponent;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;

/**
 * Another dummy component for test purposes
 */
#[Flow\Proxy(false)]
final readonly class AnotherComponent extends AbstractComponentPresentationObject
{
    public function __construct(
        public int $number
    ) {
    }
}
