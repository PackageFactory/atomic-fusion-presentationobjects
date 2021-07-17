<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if an invalid MyIntPseudoEnum was tried to be initialized
 * @Flow\Proxy(false)
 */
final class MyIntPseudoEnumIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(int $attemptedValue): self
    {
        return new self('Given value ' . $attemptedValue . ' is no valid MyIntPseudoEnum, must be one of the defined constants.', 1626511153);
    }
}
