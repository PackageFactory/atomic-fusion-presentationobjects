<?php declare(strict_types=1);
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
    /**
     * @return self
     */
    public static function becauseNoneIsConfiguredAndNoSitePackageIsAvailable(): self
    {
        return new self('No package could be resolved for component generation. Please specify a package key, configure a default or create a site package', 1582673201);
    }

    /**
     * @param string $packageKey
     * @return self
     */
    public static function becauseGivenPackageKeyDoesNotReferToAFlowPackage(string $packageKey): self
    {
        return new self('No package could be resolved for component generation, because the given package key "' . $packageKey . '" does not refer to a flow package.', 1582673202);
    }

    /**
     * @param string $packageKey
     * @return self
     */
    public static function becauseDefaultPackageKeyDoesNotReferToAFlowPackage(string $packageKey): self
    {
        return new self('No package could be resolved for component generation, because the default package key "' . $packageKey . '" does not refer to a flow package.', 1582673202);
    }
}
