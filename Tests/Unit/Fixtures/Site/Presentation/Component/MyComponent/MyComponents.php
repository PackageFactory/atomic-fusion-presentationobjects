<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * A list of myComponents
 * @Flow\Proxy(false)
 */
final class MyComponents implements \IteratorAggregate
{
    /**
     * @var array<int,MyComponentInterface>|MyComponentInterface[]
     */
    private array $myComponents;

    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof MyComponentInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . MyComponentInterface::class);
            }
        }
        $this->myComponents = $array;
    }

    /**
     * @return \ArrayIterator<int,MyComponentInterface>|MyComponentInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->myComponents);
    }
}
