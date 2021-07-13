<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\AnotherComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\AbstractComponentArray;

/**
 * A dummy component array
 * @Flow\Proxy(false)
 */
final class AnotherComponents extends AbstractComponentArray
{
    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof AnotherComponent) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . AnotherComponentInterface::class);
            }
        }
        parent::__construct($array);
    }
}
