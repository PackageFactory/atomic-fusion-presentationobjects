<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\AnotherComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;

/**
 * Another dummy component for test purposes
 * @Flow\Proxy(false)
 */
final class AnotherComponent extends AbstractComponentPresentationObject implements AnotherComponentInterface
{
    private int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
