<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\AnotherComponent;

use Neos\Flow\Annotations as Flow;

/**
 * A list of anotherComponents for testing purposes
 * @implements \IteratorAggregate<AnotherComponent>
 */
#[Flow\Proxy(false)]
final readonly class AnotherComponents implements \IteratorAggregate, \Countable
{
    /**
     * @var array<AnotherComponent>
     */
    private array $anotherComponents;

    public function __construct(AnotherComponent ...$anotherComponents)
    {
        $this->anotherComponents = $anotherComponents;
    }

    /**
     * @return \Iterator<AnotherComponent>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->anotherComponents);
    }

    public function count(): int
    {
        return count($this->anotherComponents);
    }
}
