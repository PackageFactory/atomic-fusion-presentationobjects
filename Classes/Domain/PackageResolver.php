<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Package\PackageInterface;
use Neos\Flow\Package\PackageManager;

/**
 * The package resolver domain service
 *
 * @Flow\Scope("singleton")
 */
final class PackageResolver
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
            return $this->packageManager->getPackage($packageKey);
        }
        if ($this->defaultPackageKey) {
            return $this->packageManager->getPackage($this->defaultPackageKey);
        }

        foreach ($this->packageManager->getAvailablePackages() as $availablePackage) {
            /** @var PackageInterface $availablePackage */
            if ($availablePackage->getComposerManifest('type') === 'neos-site') {
                return $availablePackage;
            }
        }

        throw NoPackageCouldBeResolved::becauseNoneIsConfiguredAndNoSitePackageIsAvailable();
    }
}
