<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface;

/**
 * Dummy string enum for test purposes
 * @Flow\Proxy(false)
 */
final class MyStringEnum implements EnumInterface
{
    private string $value;

    public static function getValues(): array
    {
        return [
            'myValue'
        ];
    }
}
