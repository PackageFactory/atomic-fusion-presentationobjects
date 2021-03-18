<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class PluralName
{
    public static function forName(string $name): string
    {
        return \mb_substr($name, -1) === 'y'
            ? \mb_substr($name, 0, \mb_strlen($name) - 1) . 'ies'
            : $name . 's';
    }

    public static function toName(string $pluralName): string
    {
        return \mb_substr($pluralName, -3) === 'ies'
            ? \mb_substr($pluralName, 0, \mb_strlen($pluralName) - 3) . 'y'
            : \mb_substr($pluralName, 0, \mb_strlen($pluralName) - 1);
    }
}
