<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IsEnum;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;

#[Flow\Proxy(false)]
final class ComponentName
{
    public function __construct(
        public readonly PackageKey $packageKey,
        public readonly FusionNamespace $fusionNamespace,
        public readonly string $name
    ) {
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

    public static function fromFusionPath(string $fusionPath): self
    {
        $startingPoint = \mb_strrpos($fusionPath, '<') + 1;
        $endpoint = \mb_strrpos($fusionPath, '>');
        $fullyQualifiedFusionName = \mb_substr($fusionPath, $startingPoint, $endpoint - $startingPoint);

        list($serializedPackageKey, $componentNamespaceAndName) = explode(':', $fullyQualifiedFusionName);
        $packageKey = new PackageKey($serializedPackageKey);

        $pivot = \mb_strrpos($componentNamespaceAndName, '.') ?: null;
        $fusionNamespace = FusionNamespace::fromString(\mb_substr($componentNamespaceAndName, 0, $pivot));
        $name = \mb_substr($componentNamespaceAndName, $pivot + 1);

        return new self(
            $packageKey,
            $fusionNamespace,
            $name
        );
    }

    public function mergeInput(string $input): self
    {
        if (\mb_strrpos($input, ':') !== false) {
            list($serializedPackageKey, $componentNamespaceAndName) = explode(':', $input);
            $packageKey = new PackageKey($serializedPackageKey);
        } else {
            $componentNamespaceAndName = $input;
            $packageKey = $this->packageKey;
        }

        if (\mb_strrpos($componentNamespaceAndName, '.') !== false) {
            $pivot = \mb_strrpos($componentNamespaceAndName, '.');
            $fusionNamespace = FusionNamespace::fromString(\mb_substr($componentNamespaceAndName, 0, $pivot));
            $name = \mb_substr($componentNamespaceAndName, $pivot + 1);
        } else {
            $fusionNamespace = $this->fusionNamespace;
            $name = $componentNamespaceAndName;
        }

        return new self(
            $packageKey,
            $fusionNamespace,
            $name
        );
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

    /**
     * @return class-string<mixed>
     */
    public function getFullyQualifiedFactoryName(): string
    {
        /** @phpstan-var class-string<mixed> $className */
        $className = $this->getPhpNamespace() . '\\' . $this->getSimpleFactoryName();
        return $className;
    }

    public function getSimpleInterfaceName(): string
    {
        return $this->name . 'Interface';
    }

    /**
     * @return class-string<mixed>
     */
    public function getFullyQualifiedInterfaceName(): string
    {
        /** @phpstan-var class-string<mixed> $className */
        $className = $this->getPhpNamespace() . '\\' . $this->getSimpleInterfaceName();
        return $className;
    }

    public function getSimpleClassName(): string
    {
        return $this->name;
    }

    /**
     * @return class-string<mixed>
     */
    public function getFullyQualifiedClassName(): string
    {
        /** @phpstan-var class-string<mixed> $className */
        $className = $this->getPhpNamespace() . '\\' . $this->getSimpleClassName();
        return $className;
    }

    public function getSimpleComponentArrayName(): string
    {
        return PluralName::forName($this->name);
    }

    public function getSimpleComponentArrayPropertyName(): string
    {
        return lcfirst(PluralName::forName($this->name));
    }

    /**
     * @return class-string<mixed>
     */
    public function getFullyQualifiedComponentArrayName(): string
    {
        /** @phpstan-var class-string<mixed> $className */
        $className = $this->getPhpNamespace() . '\\' . $this->getSimpleComponentArrayName();
        return $className;
    }

    /**
     * @param ComponentName $parentComponentName
     * @return class-string<mixed>
     */
    public function getFullyQualifiedEnumName(ComponentName $parentComponentName): string
    {
        /** @phpstan-var class-string<mixed> $className */
        $className = implode('\\', [
            $this->packageKey->toPhpNamespace(),
            'Presentation',
            $this->fusionNamespace->toPhpNameSpace(),
            $parentComponentName->name,
            $this->name
        ]);
        return $className;
    }

    public function getHelperName(): string
    {
        return $this->packageKey->getSimpleName() . '.' . $this->name;
    }

    public function getPhpFilePath(string $packagePath, bool $colocate): string
    {
        return $colocate
            ? $this->getFusionFilePath($packagePath)
            : $packagePath . '/Classes/Presentation/' . $this->fusionNamespace->toFilePath() . '/' . $this->name;
    }

    public function getInterfacePath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/'. $this->name . 'Interface.php';
    }

    public function getClassPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/'. $this->name . '.php';
    }

    public function getFactoryPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/'. $this->name . 'Factory.php';
    }

    public function getComponentArrayPath(string $packagePath, bool $colocate): string
    {
        return $this->getPhpFilePath($packagePath, $colocate) . '/'. PluralName::forName($this->name) . '.php';
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
