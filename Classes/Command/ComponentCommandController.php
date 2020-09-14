<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Command;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Value\ValueGenerator;

/**
 * The command controller for component handling
 */
class ComponentCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var ComponentGenerator
     */
    protected $componentGenerator;

    /**
     * @deprecated 2.0
     * @Flow\Inject
     * @var ValueGenerator
     */
    protected $valueGenerator;

    public function kickStartCommand(string $name, string $packageKey = null): void
    {
        $this->componentGenerator->generateComponent($name, $this->request->getExceedingArguments(), $packageKey);
    }

    /**
     * @deprecated 2.0
     * @param string $componentName
     * @param string $name
     * @param string $type
     * @param array $values
     * @param string $packageKey
     * @return void
     */
    public function kickStartValueCommand(string $componentName, string $name, string $type, array $values = null, string $packageKey = null): void
    {
        $this->valueGenerator->generateValue($componentName, $name, $type, $values, $packageKey);
    }
}
