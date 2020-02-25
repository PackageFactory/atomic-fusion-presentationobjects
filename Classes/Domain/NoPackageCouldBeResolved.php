<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if no package could be resolved
 *
 * @Flow\Proxy(false)
 */
class NoPackageCouldBeResolved extends \InvalidArgumentException
{
    public static function becauseNoneIsConfiguredAndNoSitePackageIsAvailable(): self
    {
        return new self('No package could be resolved for component generation. Please specify a package key, configure a default or create a site package', 1582673201);
    }
}
