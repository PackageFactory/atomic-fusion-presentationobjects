<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Text;

use Neos\Flow\Annotations as Flow;

/**
 * A list of texts for testing purposes
 */
#[Flow\Proxy(false)]
final class Texts implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,Text>
     */
    private array $texts;

    public function __construct(Text ...$texts)
    {
        $this->texts = $texts;
    }

    /**
     * @return \ArrayIterator<int,Text>|Text[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->texts);
    }

    public function count(): int
    {
        return count($this->texts);
    }
}
