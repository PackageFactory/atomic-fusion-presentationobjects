<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Helper;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Neos\Domain\Service\ContentContext;

/**
 * The easily accessible dummy node for usage in unit tests, e.g. for presentation object factories
 */
class DummyContext extends ContentContext
{
    public $isInBackend = false;

    public function isInBackend(): bool
    {
        return $this->isInBackend;
    }
}
