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

    public function kickStartCommand(string $packageKey, string $componentName): void
    {
        $this->componentGenerator->generateComponent($packageKey, $componentName, $this->request->getExceedingArguments());
    }

    public function kickStartValueCommand(string $packageKey, string $componentName, string $name, string $type, array $values = null, bool $createDataSource = false): void
    {
        $this->valueGenerator->generateValue($packageKey, $componentName, $name, $type, $values, $createDataSource);
    }
}
