<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class FusionNamespace
{
    /**
     * @var array|string[]
     */
    private array $segments = [];

    /**
     * @param array|string[] $segments
     */
    private function __construct(array $segments)
    {
        $this->segments = $segments;
    }

    public static function default(): self
    {
        return new self(['Component']);
    }

    public static function fromString(string $string): self
    {
        return new self(array_map(
            function(string $segment) {
                return ucfirst($segment);
            },
            explode('.', $string)
        ));
    }

    public function toFilePath(): string
    {
        return implode('/', $this->segments);
    }

    public function __toString(): string
    {
        return implode('.', $this->segments);
    }
}
