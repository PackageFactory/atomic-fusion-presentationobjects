<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Link;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

/**
 * Link component for test purposes
 */
#[Flow\Proxy(false)]
final class Link extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly UriInterface $uri,
        public readonly ?string $title
    ) {
    }
}
