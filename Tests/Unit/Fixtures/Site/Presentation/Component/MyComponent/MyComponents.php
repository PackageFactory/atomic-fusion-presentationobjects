<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\AbstractComponentArray;

/**
 * A dummy component array
 * @Flow\Proxy(false)
 */
final class MyComponents extends AbstractComponentArray
{
    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof MyComponentInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . MyComponentInterface::class);
            }
        }
        parent::__construct($array);
    }

    /**
     * @param mixed $key
     * @return MyComponentInterface|false
     */
    public function offsetGet($key)
    {
        return parent::offsetGet($key);
    }

    /**
     * @return array|MyComponentInterface[]
     */
    public function getArrayCopy(): array
    {
        return parent::getArrayCopy();
    }

    /**
     * @return \ArrayIterator|MyComponentInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return parent::getIterator();
    }
}
