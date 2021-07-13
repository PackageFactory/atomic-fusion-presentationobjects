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
     * This command will create an <b>interface</b>, a <b>value object</b> and a
     * <b>factory</b> under in the chosen component namespace. It'll also register
     * the factory for later use in Fusion.
     *
     * The remaining arguments of this command are interpreted as a list of
     * <b>property descriptors</b> which consist of a property name and a type name
     * separated by a colon (e.g.: "title:string").
     *
     * The following values are allowed for types:
     *
     * * string, int, float, bool
     * * Value class names created with <u>component:kickstartvalue</u> in the same
     *   component namespace
     * * Component class names created with <u>component:kickstart</u> in the same
     *   package
     * * ImageSource
     * * Uri
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
     * This command will create a <b>value object</b> for a pseudo-enum under in the
     * chosen component namespace and under the provided name. It'll also create a
     * co-located <b>exception</b> class that will be used when validation for the
     * pseudo-enum fails.
     *
     * Additionally, a <b>datasource</b> for use in SelectBoxEditors will be created
     * in the Application namespace of your chosen package.
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
