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
     * @Flow\Inject
     * @var ValueGenerator
     */
    protected $valueGenerator;

    /**
     * @param string $name
     * @param null|string $packageKey
     * @return void
     */
    public function kickStartCommand(string $name, ?string $packageKey = null): void
    {
        $this->componentGenerator->generateComponent($name, $this->request->getExceedingArguments(), $packageKey);
    }

    /**
     * @param string $componentName
     * @param string $name
     * @param string $type
     * @param array|string[] $values
     * @param null|string $packageKey
     * @return void
     */
    public function kickStartValueCommand(string $componentName, string $name, string $type, array $values = [], ?string $packageKey = null): void
    {
        $this->valueGenerator->generateValue($componentName, $name, $type, $values, $packageKey);
    }
}
