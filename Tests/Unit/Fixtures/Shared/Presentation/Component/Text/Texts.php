<?php

/*
 * This file is part of the Vendor.Shared package
 */

declare(strict_types=1);

namespace Vendor\Shared\Presentation\Component\Text;

use Neos\Flow\Annotations as Flow;

/**
 * A list of texts for testing purposes
 * @implements \IteratorAggregate<Text>
 */
#[Flow\Proxy(false)]
final readonly class Texts implements \IteratorAggregate, \Countable
{
    /**
     * @var array<Text>
     */
    private array $texts;

    public function __construct(Text ...$texts)
    {
        $this->texts = $texts;
    }

    /**
     * @return \Iterator<Text>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->texts);
    }

    public function count(): int
    {
        return count($this->texts);
    }
}
