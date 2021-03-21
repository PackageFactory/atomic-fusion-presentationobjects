<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

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
            'ucfirst',
            explode('.', $string)
        ));
    }

    public static function fromInput(string $input): self
    {
        if (\mb_substr_count($input, '.') > 1) {
            return self::fromString(\mb_substr($input, 0, \mb_strrpos($input, '.')));
        }

        return self::default();
    }

    public function toFilePath(): string
    {
        return implode('/', $this->segments);
    }

    public function toPhpNameSpace(): string
    {
        return implode('\\', $this->segments);
    }

    public function __toString(): string
    {
        return implode('.', $this->segments);
    }
}
