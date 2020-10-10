<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * The generic abstract component presentation object implementation
 */
abstract class AbstractComponentPresentationObject implements ComponentPresentationObjectInterface
{
    /**
     * Catches all internal EEL magic calls
     *
     * @param string $name
     * @phpstan-param array<mixed> $arguments
     * @param array $arguments
     * @return void
     */
    final public function __call($name, $arguments)
    {
        throw new \BadMethodCallException('"' . $name . '" is not part of the component API for ' . __CLASS__ . '. Please check your Fusion presentation component for typos.', 1578905708);
    }
}
