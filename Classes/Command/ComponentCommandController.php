<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Command;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolver;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\DefensiveConfirmationFileWriter;

/**
 * The command controller for kick-starting PresentationObject components
 */
class ComponentCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var PackageResolver
     */
    protected $packageResolver;

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
     * * slot
     * * Value class names created with <u>component:kickstartvalue</u> in the same
     *   component namespace
     * * Component class names created with <u>component:kickstart</u> in the same
     *   package
     * * ImageSource
     * * Uri
     * * array<...> with any of the above as an argument
     *
     * @param string $name The name of the new component
     * @param bool $listable If set, an additional list type will be generated
     * @param bool $yes If set, no confirmation is going to be required for overwriting files
     * @return void
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function kickStartCommand(string $name, bool $listable = false, bool $yes = false): void
    {
        $componentGenerator = new ComponentGenerator(
            new DefensiveConfirmationFileWriter($this->output, $yes)
        );
        $package = $this->packageResolver->resolvePackage();

        $componentGenerator->generateComponent(
            ComponentName::fromInput($name, PackageKey::fromPackage($package)),
            $this->request->getExceedingArguments(),
            $package->getPackagePath(),
            $listable
        );
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
     * @param array|string[] $values A comma-separated colon list of names:values for the new pseudo-enum, e.g. a,b,c , a:1,b:2,c:3 or a:1.2,b:2.4,c:3.6
     * @param bool $yes If set, no confirmation is going to be required for overwriting files
     * @return void
     */
    public function kickStartEnumCommand(string $componentName, string $name, string $type, array $values = [], bool $yes = false): void
    {
        $enumGenerator = new EnumGenerator(
            new \DateTimeImmutable(),
            new DefensiveConfirmationFileWriter($this->output, $yes)
        );
        $package = $this->packageResolver->resolvePackage();

        $enumGenerator->generateEnum(
            ComponentName::fromInput($componentName, PackageKey::fromPackage($package)),
            $name,
            $type,
            $values,
            $package->getPackagePath()
        );
    }
}
