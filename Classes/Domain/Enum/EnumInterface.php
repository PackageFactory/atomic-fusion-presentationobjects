<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The interface to be implemented by enums
 */
interface EnumInterface
{
    /**
     * @return array<mixed>
     */
    public static function getValues(): array;
}
