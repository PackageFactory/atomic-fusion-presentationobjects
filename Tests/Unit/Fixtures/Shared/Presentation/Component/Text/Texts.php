<?php declare(strict_types=1);
namespace Vendor\Shared\Presentation\Component\Text;

/*
 * This file is part of the Vendor.Shared package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\AbstractComponentArray;

/**
 * A text component array
 * @Flow\Proxy(false)
 */
final class Texts extends AbstractComponentArray
{
    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof TextInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . TextInterface::class);
            }
        }
        parent::__construct($array);
    }

    /**
     * @param mixed $key
     * @return TextInterface|false
     */
    public function offsetGet($key)
    {
        return parent::offsetGet($key);
    }

    /**
     * @return array|TextInterface[]
     */
    public function getArrayCopy(): array
    {
        return parent::getArrayCopy();
    }

    /**
     * @return \ArrayIterator|TextInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return parent::getIterator();
    }
}
