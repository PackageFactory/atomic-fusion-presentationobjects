<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class FusionNamespace
{
    private function __construct(
        /** @var array<string> */
        private array $segments = []
    ) {
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
            $rightMostDotPosition = \mb_strrpos($input, '.');
            return self::fromString(\mb_substr($input, 0, $rightMostDotPosition === false ? null : $rightMostDotPosition));
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
