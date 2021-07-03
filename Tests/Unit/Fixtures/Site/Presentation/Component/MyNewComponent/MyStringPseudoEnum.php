<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * Dummy string enum for test purposes
 * @Flow\Proxy(false)
 */
final class MyStringPseudoEnum implements PseudoEnumInterface
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return array<MyStringPseudoEnum>
     */
    public static function cases(): array
    {
        return [
            new self('myValue')
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
