<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\FlowPackageInterface;
use Neos\Flow\Package\PackageManager;

/**
 * The package resolver domain service
 */
#[Flow\Scope('singleton')]
final class PackageResolver implements PackageResolverInterface
{
    #[Flow\InjectConfiguration(path: 'componentGeneration.defaultPackageKey')]
    protected string $defaultPackageKey = '';

    public function __construct(
        private readonly PackageManager $packageManager
    ) {
    }

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

        throw NoPackageCouldBeResolved::becauseNoneIsConfiguredAndNoSitePackageIsAvailable();
    }
}
