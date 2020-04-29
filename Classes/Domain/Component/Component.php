<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

/**
 * @Flow\Proxy(false)
 */
final class Component
{
    /**
     * @var string
     */
    private $packageKey;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array|PropType[];
     */
    private $props;

    public function __construct(string $packageKey, string $name, array $props, ?PropTypeRepositoryInterface $propTypeRepository = null)
    {
        foreach ($props as &$propType) {
            if (is_string($propType)) {
                $propType = $propTypeRepository->findByType($packageKey, $name, $propType);
            }
        }
        $this->packageKey = $packageKey;
        $this->name = $name;
        $this->props = $props;
    }

    public static function fromInput(string $packageKey, string $name, array $serializedProps, PropTypeRepositoryInterface $propTypeRepository): self
    {
        $props = [];
        foreach ($serializedProps as $serializedProp) {
            list($propName, $serializedPropType) = explode(':', $serializedProp);
            $propType = $propTypeRepository->findByType($packageKey, $name, $serializedPropType);
            if (is_null($propType)) {
                throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($serializedPropType);
            }
            $props[$propName] = $propType;
        }

        return new self(
            $packageKey,
            $name,
            $props
        );
    }

    public function getPackageKey(): string
    {
        return $this->packageKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|PropType[]
     */
    public function getProps()
    {
        return $this->props;
    }

    public function isLeaf(): bool
    {
        foreach ($this->props as $propType) {
            if ($propType->getClass()->isComponent()) {
                return false;
            }
        }

        return true;
    }

    public function getType(): ComponentType
    {
        foreach ($this->props as $propType) {
            if ($propType->getClass()->isComponent()) {
                return ComponentType::composite();
            }
        }

        return ComponentType::leaf();
    }

    public function getFactoryName(): string
    {
        return $this->getNamespace() . '\\' . $this->name . 'Factory';
    }

    public function getHelperName(): string
    {
        return \mb_substr($this->getPackageKey(), \mb_strrpos($this->getPackageKey(), '.') + 1) . '.' . $this->getName();
    }

    public function getInterfacePath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . 'Interface.php';
    }

    public function getClassPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . '.php';
    }

    public function getFactoryPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . 'Factory.php';
    }

    public function getFusionPath(string $packagePath): string
    {
        return $packagePath . 'Resources/Private/Fusion/Presentation/' . ucfirst($this->getType()) . '/' . $this->name . '/' . $this->name . '.fusion';
    }

    public function getInterfaceContent(): string
    {
        return '<?php
namespace ' . $this->getNamespace() . ';

/*
 * This file is part of the ' . $this->getPackageKey() . ' package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
' . $this->renderUseStatements() . '
interface ' . $this->getName() . 'Interface extends ComponentPresentationObjectInterface
{
    ' . trim (implode("\n\n    ", $this->getAccessors(true))) .  '
}
';
    }

    public function getClassContent(): string
    {
        return '<?php
namespace ' . $this->getNamespace() . ';

/*
 * This file is part of the ' . $this->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
' . $this->renderUseStatements() . '
/**
 * @Flow\Proxy(false)
 */
final class ' . $this->getName() . ' extends AbstractComponentPresentationObject implements ' . $this->getName() . 'Interface
{
    ' . trim (implode("\n\n    ", $this->getProperties())) .  '

    ' . $this->renderConstructor() .  '

    ' . trim (implode("\n\n    ", $this->getAccessors(false))) .  '
}
';
    }

    public function getFactoryContent(): string
    {
        return '<?php
namespace ' . $this->getNamespace() . ';

/*
 * This file is part of the ' . $this->getPackageKey() . ' package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class ' . $this->getName() . 'Factory extends AbstractComponentPresentationObjectFactory
{
}
';
    }

    public function getFusionContent(): string
    {
        $terms = [];
        $styleGuideProps = [];
        foreach ($this->props as $propName => $propType) {
            if ($propType->getFullyQualifiedName() === ImageSourceHelperInterface::class) {
                $definitionData = '<Sitegeist.Lazybones:Image imageSource={presentationObject.' . $propName . '}' . ($propType->isNullable() ? ' @if.isToBeRendered={presentationObject.' . $propName. '}' : '') . ' />';
            } elseif ($propType->getClass()->isComponent()) {
                $definitionData = '<' . $this->packageKey . ':' . ucfirst($propType->getClass()) . '.' . $propType->getName() . ' presentationObject={presentationObject.' . $propName . '}' . ($propType->isNullable() ? ' @if.isToBeRendered={presentationObject.' . $propName. '}' : '') . ' />';
            } else {
                $definitionData = '{presentationObject.' . $propName . '}';
            }
            $styleGuideProps[] = $propName . ' ' . $propType->toStyleGuidePropValue();
                $terms[] = '        <dt>' . $propName . ':</dt>
        <dd>' . $definitionData . '</dd>';
        }

        return 'prototype(' . $this->packageKey . ':' . ucfirst($this->getType()) . '.' . $this->name . ') < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'' . $this->getNamespace() .  '\\' . $this->name . 'Interface\'

    @styleguide {
        title = \'' . $this->name . '\'

        props {
            ' . implode("\n            ", $styleGuideProps) .'
        }
    }

    renderer = afx`<dl>
        ' . trim(implode("\n", $terms)) . '
    </dl>`
}
';
    }

    private function getNamespace(): string
    {
        return \str_replace('.', '\\', $this->packageKey) . '\Presentation\\' . $this->name;
    }

    private function getProperties(): array
    {
        $properties = [];
        foreach ($this->props as $propName => $propType) {
            $properties[] = '/**
     * @var ' . $propType->toVar() . '
     */
    private $' . $propName . ';';
        }

        return $properties;
    }

    private function renderUseStatements(): string
    {
        $statements = '';

        $statedTypes = [];
        foreach ($this->props as $propType) {
            if (!$propType->getClass()->isPrimitive() && \mb_strpos($propType->getFullyQualifiedName(), $this->getNamespace()) !== 0 && !isset($statedTypes[$propType->getSimpleName()])) {
                $statedTypes[$propType->getSimpleName()] = true;
                $statements .= 'use ' . $propType->getFullyQualifiedName() . ';
';
            }
        }

        return $statements;
    }

    private function renderConstructor(): string
    {
        $arguments = [];
        $setters = [];
        foreach ($this->props as $propName => $propType) {
            $arguments[] = $propType->toType() . ' $' . $propName . ',';
            $setters[] = '$this->' . $propName . ' = $' . $propName . ';';
        }
        return 'public function __construct(
        ' . trim(trim(implode("\n        ", $arguments)), ',') .  '
    ) {
        ' . trim(implode("\n        ", $setters)) .  '
    }';
    }

    private function getAccessors(bool $abstract = false): array
    {
        $accessors = [];
        foreach ($this->props as $propName => $propType) {
            $accessorHeader =  'public function get' . ucfirst($propName) . '(): ' . $propType->toType();

            $accessors[] = $accessorHeader . ($abstract ? ';' : '
    {
        return $this->' . $propName . ';
    }') ;
        }

        return $accessors;
    }
}
