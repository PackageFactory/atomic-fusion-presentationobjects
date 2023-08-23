<?php

/*
 * This file is part of the Vendor.Site package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Custom\Type\MyComponent;

use Neos\Flow\Annotations as Flow;

/**
 * A list of myComponents for test purposes
 * @implements \IteratorAggregate<int,MyComponent>
 */
#[Flow\Proxy(false)]
final readonly class MyComponents implements \IteratorAggregate, \Countable
{
    /**
     * @var array<MyComponent>
     */
    private array $myComponents;

    public function __construct(MyComponent ...$myComponents)
    {
        $this->myComponents = $myComponents;
    }

    /**
     * @return \Iterator<MyComponent>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->myComponents);
    }

    public function count(): int
    {
        return count($this->myComponents);
    }
}
