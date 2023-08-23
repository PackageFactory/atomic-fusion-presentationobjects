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
final readonly class Link extends AbstractComponentPresentationObject
{
    public function __construct(
        public UriInterface $uri,
        public ?string $title
    ) {
    }
}
