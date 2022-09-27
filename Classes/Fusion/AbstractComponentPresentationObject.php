<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * The generic abstract component presentation object implementation
 */
abstract class AbstractComponentPresentationObject implements ComponentPresentationObjectInterface, SlotInterface
{
    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        $componentName = ComponentName::fromClassName(static::class);
        return $componentName->getFullyQualifiedFusionName();
    }
}
