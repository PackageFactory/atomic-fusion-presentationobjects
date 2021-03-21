<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\InvalidComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * Dummy invalid enum for test purposes
 * @Flow\Proxy(false)
 */
final class InvalidEnum
{
    private string $value;
}
