<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Headline;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if an invalid HeadlineLook was tried to be initialized
 * @Flow\Proxy(false)
 */
final class HeadlineLookIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('Given value ' . $attemptedValue . ' is no valid HeadlineLook, must be one of the defined constants.', 1626511749);
    }
}
