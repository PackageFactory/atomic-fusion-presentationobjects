<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\AnotherComponent;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * The interface for another dummy component for test purposes
 */
interface AnotherComponentInterface extends ComponentPresentationObjectInterface
{
    public function getNumber(): int;
}
