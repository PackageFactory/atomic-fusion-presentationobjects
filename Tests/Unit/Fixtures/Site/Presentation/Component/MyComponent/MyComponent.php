<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponentInterface;

/**
 * Dummy component for test purposes
 * @Flow\Proxy(false)
 */
final class MyComponent extends AbstractComponentPresentationObject implements MyComponentInterface
{
    private string $text;

    private AnotherComponentInterface $other;

    public function __construct(string $text, AnotherComponentInterface $other)
    {
        $this->text = $text;
        $this->other = $other;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getOther(): AnotherComponentInterface
    {
        return $this->other;
    }
}
