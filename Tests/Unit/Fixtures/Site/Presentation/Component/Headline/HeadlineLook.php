<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Headline;

/*
 * This file is part of the Vendor.Site package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * HeadlineLook enum for test purposes
 * @Flow\Proxy(false)
 */
final class HeadlineLook implements PseudoEnumInterface
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function cases(): array
    {
        return [
            new self('large')
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
