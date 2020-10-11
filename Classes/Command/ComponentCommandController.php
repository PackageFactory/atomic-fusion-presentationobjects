<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Command;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Value\ValueGenerator;

/**
 * The command controller for kickstarting PresentationObject components
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
     * Create a new PresentationObject component and factory
     *
     * @param string $name The name of the new component
     * @param null|string $packageKey Package key of an optional target package, if not set the configured default package or the first available site package will be used
     * @return void
     */
    public function kickStartCommand(string $name, ?string $packageKey = null): void
    {
        $this->componentGenerator->generateComponent($name, $this->request->getExceedingArguments(), $packageKey);
    }

    /**
     * Create a new pseudo-enum value object
     *
     * @param string $componentName The name of the component the new pseudo-enum belongs to
     * @param string $name The name of the new pseudo-enum
     * @param string $type The type of the new pseudo-enum (must be one of: "string", "int")
     * @param array|string[] $values A comma-separated list of values for the new pseudo-enum
     * @param null|string $packageKey Package key of an optional target package, if not set the configured default package or the first available site package will be used
     * @return void
     */
    public function kickStartValueCommand(string $componentName, string $name, string $type, array $values = [], ?string $packageKey = null): void
    {
        $this->valueGenerator->generateValue($componentName, $name, $type, $values, $packageKey);
    }
}
