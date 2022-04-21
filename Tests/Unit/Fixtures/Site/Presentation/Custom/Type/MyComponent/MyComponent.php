<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Custom\Type\MyComponent;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponent;

/**
 * Dummy component for test purposes
 */
#[Flow\Proxy(false)]
final class MyComponent extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $text,
        public readonly AnotherComponent $other
    ) {
    }
}
