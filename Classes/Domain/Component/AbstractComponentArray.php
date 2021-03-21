<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Domain\AbstractImmutableArrayObject;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * The abstract class for component array objects
 * @extends AbstractImmutableArrayObject<int,ComponentPresentationObjectInterface>
 */
abstract class AbstractComponentArray extends AbstractImmutableArrayObject
{
}
