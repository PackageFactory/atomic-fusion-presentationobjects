<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class EnumTypeIsInvalid extends \DomainException
{
    public static function becauseItIsNoneOfTheSupportedTypes(string $attemptedType): self
    {
        return new self('Only type string, int or float are supported at this point.', 1582502049);
    }
}
