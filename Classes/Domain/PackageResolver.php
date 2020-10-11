<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Package\PackageManager;

/**
 * The package resolver domain service
 *
 * @Flow\Scope("singleton")
 */
final class PackageResolver implements PackageResolverInterface
{
    /**
     * @Flow\Inject
     * @var PackageManager
     */
    protected $packageManager;

    /**
     * @Flow\InjectConfiguration(path="componentGeneration.defaultPackageKey")
     * @var string
     */
    protected $defaultPackageKey;

    public function resolvePackage(?string $packageKey = null): FlowPackageInterface
    {
        if ($packageKey) {
            $package = $this->packageManager->getPackage($packageKey);
            if ($package instanceof FlowPackageInterface) {
                return $package;
            } else {
                throw NoPackageCouldBeResolved::
                    becauseGivenPackageKeyDoesNotReferToAFlowPackage($packageKey);
            }
        }
        if ($this->defaultPackageKey) {
            $package = $this->packageManager->getPackage($this->defaultPackageKey);
            if ($package instanceof FlowPackageInterface) {
                return $package;
            } else {
                throw NoPackageCouldBeResolved::
                    becauseDefaultPackageKeyDoesNotReferToAFlowPackage($this->defaultPackageKey);
            }
        }

        foreach ($this->packageManager->getAvailablePackages() as $availablePackage) {
            /** @var FlowPackageInterface $availablePackage */
            if ($availablePackage->getComposerManifest('type') === 'neos-site') {
                return $availablePackage;
            }
        }

        throw NoPackageCouldBeResolved::
            becauseNoneIsConfiguredAndNoSitePackageIsAvailable();
    }
}
