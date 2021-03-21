<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Headline;

/*
 * This file is part of the Vendor.Site package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface;

/**
 * HeadlineType enum for test purposes
 * @Flow\Proxy(false)
 */
final class HeadlineType implements EnumInterface
{
    private string $value;

    public static function getValues(): array
    {
        return [
            'h1'
        ];
    }
}
