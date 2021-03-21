<?php

namespace Vendor\Site\Presentation\CustomType\MyComponent;


use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponentInterface;

/**
 * Dummy component for test purposes
 * @Flow\Proxy(false)
 */
interface MyComponentInterface
{
    public function getText(): string;

    public function getOther(): AnotherComponentInterface;
}
