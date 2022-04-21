<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\AnotherComponent;

use Neos\Flow\Annotations as Flow;

/**
 * A list of anotherComponents for testing purposes
 * @implements \IteratorAggregate<int,AnotherComponent>
 */
#[Flow\Proxy(false)]
final class AnotherComponents implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,AnotherComponent>|AnotherComponent[]
     */
    private array $anotherComponents;

    public function __construct(AnotherComponent ...$anotherComponents)
    {
        $this->anotherComponents = $anotherComponents;
    }

    /**
     * @return \ArrayIterator<int,AnotherComponent>|AnotherComponent[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->anotherComponents);
    }

    public function count(): int
    {
        return count($this->anotherComponents);
    }
}
