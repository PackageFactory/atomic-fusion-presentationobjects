<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponentInterface;

/**
 * Dummy component interface for test purposes
 */
interface MyComponentInterface extends ComponentPresentationObjectInterface
{
    public function getText(): string;

    public function getOther(): AnotherComponentInterface;
}
