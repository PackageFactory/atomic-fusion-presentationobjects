<?php

/*
 * This file is part of the Vendor.Shared package
 */

declare(strict_types=1);

namespace Vendor\Shared\Presentation\Component\Text;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;

/**
 * Text component for test purposes
 */
#[Flow\Proxy(false)]
final readonly class Text extends AbstractComponentPresentationObject
{
    public function __construct(
        public string $text
    ) {
    }
}
