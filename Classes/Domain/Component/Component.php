<?php declare(strict_types=1);
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
    private string $packageKey;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var array|PropType[];
     */
    private array $props;

    /**
     * @var FusionNamespace
     */
    private FusionNamespace $fusionNamespace;

    /**
     * @param string $packageKey
     * @param string $name
     * @param array|PropType[] $props
     * @param FusionNamespace $fusionNamespace
     */
    public function __construct(string $packageKey, string $name, array $props, FusionNamespace $fusionNamespace)
    {
        $this->packageKey = $packageKey;
        $this->name = $name;
        $this->props = $props;
        $this->fusionNamespace = $fusionNamespace;
    }

    /**
     * @param string $packageKey
     * @param string $name
     * @param array|string[] $serializedProps
     * @param PropTypeRepositoryInterface $propTypeRepository
     * @param FusionNamespace $fusionNamespace
     * @return self
     */
    public static function fromInput(
        string $packageKey,
        string $name,
        array $serializedProps,
        PropTypeRepositoryInterface $propTypeRepository,
        FusionNamespace $fusionNamespace
    ): self {
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
            $props,
            $fusionNamespace
        );
    }

    /**
     * @return string
     */
    public function getPackageKey(): string
    {
        return $this->packageKey;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|PropType[]
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * @return FusionNamespace
     */
    public function getFusionNamespace(): FusionNamespace
    {
        return $this->fusionNamespace;
    }

    /**
     * @return string
     */
    public function getFactoryName(): string
    {
        return $this->getNamespace() . '\\' . $this->name . 'Factory';
    }

    /**
     * @return string
     */
    public function getHelperName(): string
    {
        return \mb_substr($this->getPackageKey(), \mb_strrpos($this->getPackageKey(), '.') + 1) . '.' . $this->getName();
    }

    /**
     * @param string $packagePath
     * @return string
     */
    public function getInterfacePath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . 'Interface.php';
    }

    /**
     * @param string $packagePath
     * @return string
     */
    public function getClassPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . '.php';
    }

    /**
     * @param string $packagePath
     * @return string
     */
    public function getFactoryPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->name . '/' . $this->name . 'Factory.php';
    }

    /**
     * @param string $packagePath
     * @return string
     */
    public function getFusionPath(string $packagePath): string
    {
        return $packagePath . 'Resources/Private/Fusion/Presentation/' . $this->getFusionNamespace()->toFilePath() . '/' . $this->name . '/' . $this->name . '.fusion';
    }

    /**
     * @return string
     */
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
    ' . trim(implode("\n\n    ", $this->getAccessors(true))) .  '
}
';
    }

    /**
     * @return string
     */
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
    ' . trim(implode("\n\n    ", $this->getProperties())) .  '

    ' . $this->renderConstructor() .  '

    ' . trim(implode("\n\n    ", $this->getAccessors(false))) .  '
}
';
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getFusionContent(): string
    {
        $terms = [];
        $styleGuideProps = [];
        foreach ($this->props as $propName => $propType) {
            if ($propType->getFullyQualifiedName() === ImageSourceHelperInterface::class) {
                $definitionData = '<Sitegeist.Lazybones:Image imageSource={presentationObject.' . $propName . '}' . ($propType->isNullable() ? ' @if.isToBeRendered={presentationObject.' . $propName. '}' : '') . ' />';
            } elseif ($propType->getClass()->isComponent()) {
                $definitionData = '<' . $this->packageKey . ':' . ucfirst((string) $propType->getClass()) . '.' . $propType->getName() . ' presentationObject={presentationObject.' . $propName . '}' . ($propType->isNullable() ? ' @if.isToBeRendered={presentationObject.' . $propName. '}' : '') . ' />';
            } else {
                $definitionData = '{presentationObject.' . $propName . '}';
            }
            $styleGuideProps[] = $propName . ' ' . $propType->toStyleGuidePropValue();
                $terms[] = '        <dt>' . $propName . ':</dt>
        <dd>' . $definitionData . '</dd>';
        }

        return 'prototype(' . $this->packageKey . ':' . $this->getFusionNamespace() . '.' . $this->name . ') < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'' . str_replace('\\', '\\\\', ucfirst($this->getNamespace())) .  '\\\\' . $this->name . 'Interface\'

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

    /**
     * @return string
     */
    private function getNamespace(): string
    {
        return \str_replace('.', '\\', $this->packageKey) . '\Presentation\\' . $this->name;
    }

    /**
     * @return array|string[]
     */
    private function getProperties(): array
    {
        $properties = [];
        foreach ($this->props as $propName => $propType) {
            $properties[] = 'private ' . $propType->toType() . ' $' . $propName . ';';
        }

        return $properties;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
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

    /**
     * @param boolean $abstract
     * @return array|string[]
     */
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
