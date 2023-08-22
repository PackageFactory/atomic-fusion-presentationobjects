<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumGenerator;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FactoryRendererInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageResolver;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\DefensiveConfirmationFileWriter;

/**
 * The command controller for kick-starting PresentationObject components
 */
class ComponentCommandController extends CommandController
{
    #[Flow\Inject]
    protected PackageResolver $packageResolver;

    #[Flow\InjectConfiguration(path: 'componentGeneration.colocate')]
    protected bool $colocate;

    #[Flow\Inject]
    protected FactoryRendererInterface $factoryRenderer;

    /**
     * Create a new PresentationObject component and factory
     *
     * This command will create a <b>value object</b> and a
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
     * @throws \Neos\Utility\Exception\FilesException
     */
    public function kickStartCommand(string $name, bool $listable = false, bool $yes = false): void
    {
        $componentGenerator = new ComponentGenerator(
            new DefensiveConfirmationFileWriter($this->output, $yes),
            $this->factoryRenderer
        );
        $package = $this->packageResolver->resolvePackage();
        $componentName = ComponentName::fromInput($name, PackageKey::fromPackage($package));
        $componentPackage = $this->packageResolver->resolvePackage((string)$componentName->packageKey);

        $componentGenerator->generateComponent(
            $componentName,
            $this->request->getExceedingArguments(),
            $componentPackage->getPackagePath(),
            $this->colocate,
            $listable
        );
    }

    /**
     * Create a new enum
     *
     * This command will create an enum under in the
     * chosen component namespace and under the provided name.
     *
     * @param string $componentName The name of the component the new pseudo-enum belongs to
     * @param string $name The name of the new pseudo-enum
     * @param string $type The type of the new pseudo-enum (must be one of: "string", "int")
     * @param array|string[] $values A comma-separated colon list of names:values for the new pseudo-enum, e.g. a,b,c , a:1,b:2,c:3 or a:1.2,b:2.4,c:3.6
     * @param bool $yes If set, no confirmation is going to be required for overwriting files
     */
    public function kickStartEnumCommand(string $componentName, string $name, string $type, array $values = [], bool $yes = false): void
    {
        $enumGenerator = new EnumGenerator(
            new DefensiveConfirmationFileWriter($this->output, $yes)
        );
        $package = $this->packageResolver->resolvePackage();
        $componentNameObject = ComponentName::fromInput($componentName, PackageKey::fromPackage($package));
        $componentPackage = $this->packageResolver->resolvePackage((string)$componentNameObject->packageKey);

        $enumGenerator->generateEnum(
            $componentNameObject,
            $name,
            $type,
            $values,
            $componentPackage->getPackagePath(),
            $this->colocate
        );
    }
}
