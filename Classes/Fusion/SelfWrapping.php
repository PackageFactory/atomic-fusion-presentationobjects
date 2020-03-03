<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * The trait for self-wrapping presentation components for inline editing
 *
 * The wrapper function should be created by AbstractComponentPresentationObjectFactory::createWrapper()
 */
trait SelfWrapping
{
    /**
     * @var callable|null
     */
    private $wrapper;

    final public function wrap(string $value): string
    {
        $wrapper = $this->wrapper;

        return $wrapper ? $wrapper($value) : $value;
    }
}
