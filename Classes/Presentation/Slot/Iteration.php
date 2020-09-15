<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Iteration
{
    /**
     * @var integer
     */
    private $index;

    /**
     * @var null|integer
     */
    private $count;

    /**
     * @var boolean
     */
    private $isFirst;

    /**
     * @var boolean
     */
    private $isLast;

    /**
     * @param integer $index
     * @param null|integer $count
     * @param boolean $isFirst
     * @param boolean $isLast
     */
    private function __construct(
        int $index,
        ?int $count,
        bool $isFirst,
        bool $isLast
    ) {
        $this->index = $index;
        $this->count = $count;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
    }

    /**
     * @param iterable<mixed> $iterable
     * @return self
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

    /**
     * @return integer
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return integer
     */
    public function getCycle(): int
    {
        return $this->index + 1;
    }

    /**
     * @return null|integer
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return boolean
     */
    public function isFirst(): bool
    {
        return $this->isFirst;
    }

    /**
     * @return boolean
     */
    public function isLast(): bool
    {
        return $this->isLast;
    }

    /**
     * @return boolean
     */
    public function isOdd(): bool
    {
        return $this->getCycle() % 2 === 1;
    }

    /**
     * @return boolean
     */
    public function isEven(): bool
    {
        return $this->getCycle() % 2 === 0;
    }

    /**
     * @return self
     */
    public function next(): self
    {
        $next = clone $this;
        $next->index++;
        $next->isFirst = false;
        $next->isLast = false;

        return $next;
    }

    /**
     * @return self
     */
    public function last(): self
    {
        $next = clone $this;
        $next->isLast = true;

        return $next;
    }
}
