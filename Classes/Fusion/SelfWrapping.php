<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * The trait for self-wrapping presentation components for inline editing
 *
 * The wrapper function should be created by AbstractComponentPresentationObjectFactory::createWrapper()
 * @deprecated since 3.0 Use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content for content integration purposes instead
 */
trait SelfWrapping
{
    /**
     * @var null|callable
     */
    private $wrapper;

    /**
     * @param string $value
     * @return string
     * @deprecated since 3.0 Use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content for content integration purposes instead
     */
    final public function wrap(string $value): string
    {
        $wrapper = $this->wrapper;

        return $wrapper ? $wrapper($value) : $value;
    }
}
