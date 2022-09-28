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
     * Catches all internal EEL magic calls
     *
     * @param string $name
     * @phpstan-param array<mixed> $arguments
     * @param array $arguments
     * @return void
     */
    final public function __call(string $name, array $arguments)
    {
        throw new \BadMethodCallException('"' . $name . '" is not part of the component API for ' . get_class($this) . '. Please check your Fusion presentation component for typos.', 1578905708);
    }

    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        $componentName = ComponentName::fromClassName(static::class);
        return $componentName->getFullyQualifiedFusionName();
    }
}
