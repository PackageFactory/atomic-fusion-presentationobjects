<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Iteration
{
    private function __construct(
        public int $index,
        public ?int $count,
        public bool $isFirst,
        public bool $isLast
    ) {
    }

    /**
     * @param iterable<mixed> $iterable
     */
    public static function fromIterable(iterable $iterable): self
    {
        return new self(
            0,
            is_countable($iterable) ? count($iterable) : null,
            true,
            false
        );
    }

    public function getCycle(): int
    {
        return $this->index + 1;
    }

    public function isOdd(): bool
    {
        return $this->getCycle() % 2 === 1;
    }

    public function isEven(): bool
    {
        return $this->getCycle() % 2 === 0;
    }

    public function next(): self
    {
        return new self(
            $this->index + 1,
            $this->count,
            false,
            false
        );
    }

    public function last(): self
    {
        return new self(
            $this->index,
            $this->count,
            $this->isFirst,
            true
        );
    }
}
