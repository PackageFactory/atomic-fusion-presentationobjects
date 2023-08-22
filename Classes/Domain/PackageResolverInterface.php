<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

use Neos\Flow\Package\FlowPackageInterface;

interface PackageResolverInterface
{
    /**
     * @param null|string $packageKey
     * @return FlowPackageInterface
     */
    public function resolvePackage(?string $packageKey = null): FlowPackageInterface;
}
