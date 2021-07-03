<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * Dummy int enum for test purposes
 * @Flow\Proxy(false)
 */
final class MyIntPseudoEnum implements PseudoEnumInterface
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return array<MyIntPseudoEnum>
     */
    public static function cases(): array
    {
        return [
            new self(42)
        ];
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
