<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Text;

/*
 * This file is part of the Vendor.Site package
 */

use Neos\Flow\Annotations as Flow;
use Vendor\Shared\Presentation\Component\Text\TextInterface;

/**
 * A list of texts
 * @Flow\Proxy(false)
 */
final class Texts implements \IteratorAggregate
{
    /**
     * @var array<int,TextInterface>|TextInterface[]
     */
    private array $texts;

    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof TextInterface) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . TextInterface::class);
            }
        }
        $this->texts = $array;
    }

    /**
     * @return \ArrayIterator<int,TextInterface>|TextInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->texts);
    }
}
