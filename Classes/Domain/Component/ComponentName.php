<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IsEnum;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;

/**
 * @Flow\Proxy(false)
 */
final class ComponentName
{
    private PackageKey $packageKey;

    private FusionNamespace $fusionNamespace;

    private string $name;

    public function __construct(PackageKey $packageKey, FusionNamespace $fusionNamespace, string $name)
    {
        $this->packageKey = $packageKey;
        $this->fusionNamespace = $fusionNamespace;
        $this->name = $name;
    }

    public static function fromInput(string $input, PackageKey $fallbackPackageKey): self
    {
        if (\mb_strrpos($input, ':') !== false) {
            list($serializedPackageKey, $componentNamespaceAndName) = explode(':', $input);
            $packageKey = new PackageKey($serializedPackageKey);
        } else {
            $componentNamespaceAndName = $input;
            $packageKey = $fallbackPackageKey;
        }

        if (\mb_strrpos($componentNamespaceAndName, '.') !== false) {
            $pivot = \mb_strrpos($componentNamespaceAndName, '.');
            $fusionNamespace = FusionNamespace::fromString(\mb_substr($componentNamespaceAndName, 0, $pivot));
            $name = \mb_substr($componentNamespaceAndName, $pivot + 1);
        } else {
            $fusionNamespace = FusionNamespace::default();
            $name = $componentNamespaceAndName;
        }

        return new self(
            $packageKey,
            $fusionNamespace,
            $name
        );
    }

    public static function fromClassName(string $className): self
    {
        list($packageNamespace, $componentNamespaceAndName) = explode('\\Presentation\\', $className);
        $packageKey = PackageKey::fromPhpNamespace($packageNamespace);
        $fusionNamespaceSegments = explode('\\', $componentNamespaceAndName);
        $componentNameSegments = array_splice($fusionNamespaceSegments, -2);
        $fusionNamespace = FusionNamespace::fromString(implode('.', $fusionNamespaceSegments));

        if (IsEnum::isSatisfiedByClassName($className)) {
            $componentName = $componentNameSegments[1];
        } else {
            $componentName = $componentNameSegments[0];
        }

        return new self($packageKey, $fusionNamespace, $componentName);
    }

    public function getPackageKey(): PackageKey
    {
        return $this->packageKey;
    }

    public function getFusionNamespace(): FusionNamespace
    {
        return $this->fusionNamespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullyQualifiedFusionName(): string
    {
        return $this->packageKey->toFusionNamespace() . $this->fusionNamespace . '.' . $this->name;
    }

    public function getPhpNamespace(): string
    {
        return $this->packageKey->toPhpNamespace() . '\Presentation\\' . $this->fusionNamespace->toPhpNameSpace() . '\\' . $this->name;
    }

    public function getSimpleFactoryName(): string
    {
        return $this->name . 'Factory';
    }

    public function getFullyQualifiedFactoryName(): string
    {
        return $this->getPhpNamespace() . '\\' . $this->getSimpleFactoryName();
    }

    public function getSimpleInterfaceName(): string
    {
        return $this->name . 'Interface';
    }

    public function getFullyQualifiedInterfaceName(): string
    {
        return $this->getPhpNamespace() . '\\' . $this->getSimpleInterfaceName();
    }

    public function getSimpleClassName(): string
    {
        return $this->name;
    }

    public function getFullyQualifiedClassName(): string
    {
        return $this->getPhpNamespace() . '\\' . $this->getSimpleClassName();
    }

    public function getSimpleComponentArrayName(): string
    {
        return PluralName::forName($this->name);
    }

    public function getFullyQualifiedComponentArrayName(): string
    {
        return $this->getPhpNamespace() . '\\' . $this->getSimpleComponentArrayName();
    }

    public function getFullyQualifiedEnumName(string $parentComponentName): string
    {
        return implode('\\', [
            $this->packageKey->toPhpNamespace(),
            'Presentation',
            $this->fusionNamespace->toPhpNameSpace(),
            $parentComponentName,
            $this->name
        ]);
    }

    public function getHelperName(): string
    {
        return $this->packageKey->getSimpleName() . '.' . $this->name;
    }

    public function getPhpFilePath(string $packagePath): string
    {
        return $packagePath . '/Classes/Presentation/' . $this->fusionNamespace->toFilePath() . '/' . $this->name;
    }

    public function getInterfacePath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/'. $this->name . 'Interface.php';
    }

    public function getClassPath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/'. $this->name . '.php';
    }

    public function getFactoryPath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/'. $this->name . 'Factory.php';
    }

    public function getComponentArrayPath(string $packagePath): string
    {
        return $this->getPhpFilePath($packagePath) . '/'. PluralName::forName($this->name) . '.php';
    }

    public function getFusionFilePath(string $packagePath): string
    {
        return $packagePath . '/Resources/Private/Fusion/Presentation/' . $this->fusionNamespace->toFilePath() . '/' . $this->name;
    }

    public function getFusionComponentPath(string $packagePath): string
    {
        return $this->getFusionFilePath($packagePath) . '/' . $this->name . '.fusion';
    }

    public function renderClassComment(): string
    {
        return '/*
 * This file is part of the ' . $this->packageKey . ' package.
 */';
    }
}
