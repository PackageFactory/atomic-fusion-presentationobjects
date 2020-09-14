<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Framework\Type;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Eel\ProtectedContextAwareInterface;

/**
 * A version of spatie/enum that can be used in Eel contexts
 */
abstract class Enum extends \Spatie\Enum\Enum implements ProtectedContextAwareInterface
{
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
