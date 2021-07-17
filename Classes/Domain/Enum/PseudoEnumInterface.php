<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The interface to be implemented by pseudo enums
 *
 * See https://stitcher.io/blog/php-enums
 */
interface PseudoEnumInterface
{
    /**
     * @return array<int,PseudoEnumInterface>|PseudoEnumInterface[]
     */
    public static function cases(): array;

    /**
     * @return int|string
     */
    public function getValue();
}
