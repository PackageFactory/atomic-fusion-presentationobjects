<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\AnotherComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * A list of anotherComponents
 * @Flow\Proxy(false)
 */
final class AnotherComponents implements \IteratorAggregate
{
    /**
     * @var array<int,AnotherComponentInterface>|AnotherComponentInterface[]
     */
    private array $anotherComponents;

    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof AnotherComponentInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . AnotherComponentInterface::class);
            }
        }
        $this->anotherComponents = $array;
    }

    /**
     * @return \ArrayIterator<int,AnotherComponentInterface>|AnotherComponentInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->anotherComponents);
    }
}
