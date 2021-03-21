<?php
namespace Vendor\Site\Presentation\Component\Link;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;

/**
 * Link component interface for test purposes
 */
interface LinkInterface extends ComponentPresentationObjectInterface
{
    public function getUri(): UriInterface;

    public function getTitle(): ?string;
}
