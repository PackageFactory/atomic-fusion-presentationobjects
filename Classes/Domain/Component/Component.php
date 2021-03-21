<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;

/**
 * @Flow\Proxy(false)
 */
final class Component
{
    /**
     * @var ComponentName
     */
    private ComponentName $name;

    /**
     * @var Props
     */
    private Props $props;

    /**
     * @var bool
     */
    private bool $generic;

    /**
     * @param ComponentName $name
     * @param FusionNamespace $fusionNamespace
     * @param bool $generic
     */
    public function __construct(ComponentName $name, Props $props, bool $generic)
    {
        $this->name = $name;
        $this->props = $props;
        $this->generic = $generic;
    }

    /**
     * @return bool
     */
    public function isGeneric(): bool
    {
        return $this->generic;
    }

    /**
     * @return string
     */
    public function getInterfaceContent(): string
    {
        return '<?php
namespace ' . $this->name->getPhpNamespace() . ';

' . $this->name->renderClassComment() . '

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
' . $this->props->renderUseStatements() . '
interface ' . $this->name->getSimpleInterfaceName() . ' extends ComponentPresentationObjectInterface
{
    ' . $this->renderAccessors(true) .  '
}
';
    }

    /**
     * @return string
     */
    public function getClassContent(): string
    {
        return '<?php
namespace ' . $this->name->getPhpNamespace() . ';

' . $this->name->renderClassComment() . '

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
' . $this->props->renderUseStatements() . '
/**
 * @Flow\Proxy(false)
 */
final class ' . $this->name->getSimpleClassName() . ' extends AbstractComponentPresentationObject implements ' . $this->name->getSimpleInterfaceName() . '
{
    ' . $this->renderProperties() .  '

    ' . $this->renderConstructor() .  '

    ' . $this->renderAccessors(false) .  '
}
';
    }

    /**
     * @return string
     */
    public function getFactoryContent(): string
    {
        return '<?php
namespace ' . $this->name->getPhpNamespace() . ';

' . $this->name->renderClassComment() . '

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class ' . $this->name->getSimpleFactoryName() . ' extends AbstractComponentPresentationObjectFactory
{
}
';
    }

    /**
     * @return string
     */
    public function getComponentArrayContent(): string
    {
        return '<?php
namespace ' . $this->name->getPhpNamespace() . ';

' . $this->name->renderClassComment() . '

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\AbstractComponentArray;

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->name->getSimpleComponentArrayName() . ' extends AbstractComponentArray
{
    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof ' . $this->name->getSimpleInterfaceName() . ') {
                throw new \InvalidArgumentException(self::class . \' can only consist of \' . ' . $this->name->getSimpleInterfaceName() . '::class);
            }
        }
        parent::__construct($array);
    }

    /**
     * @param mixed $key
     * @return ' . $this->name->getSimpleInterfaceName() . '|false
     */
    public function offsetGet($key)
    {
        return parent::offsetGet($key);
    }

    /**
     * @return array|'. $this->name->getSimpleInterfaceName() . '[]
     */
    public function getArrayCopy(): array
    {
        return parent::getArrayCopy();
    }

    /**
     * @return \ArrayIterator|' . $this->name->getSimpleInterfaceName() . '[]
     */
    public function getIterator(): \ArrayIterator
    {
        return parent::getIterator();
    }
}
';
    }

    /**
     * @return string
     */
    public function getFusionContent(): string
    {
        return 'prototype(' . $this->name->getFullyQualifiedFusionName() . ') < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'' . \str_replace('\\', '\\\\', $this->name->getFullyQualifiedInterfaceName()) . '\'

    @styleguide {
        title = \'' . $this->name->getName() . '\'

        props {
' . $this->props->renderStyleGuideProps() .'
        }
    }

    renderer = afx`<dl>
        ' . $this->props->renderDefinitionTerms() . '
    </dl>`
}
';
    }

    /**
     * @return string
     */
    private function renderProperties(): string
    {
        $properties = [];
        foreach ($this->props as $propName => $propType) {
            $properties[] = 'private ' . $propType->getType() . ' $' . $propName . ';';
        }

        return trim(implode("\n\n    ", $properties));
    }

    /**
     * @return string
     */
    private function renderConstructor(): string
    {
        $arguments = [];
        $setters = [];
        foreach ($this->props as $propName => $propType) {
            $arguments[] = $propType->getType() . ' $' . $propName . ',';
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
     * @return string
     */
    private function renderAccessors(bool $abstract = false): string
    {
        $accessors = [];
        foreach ($this->props as $propName => $propType) {
            $accessorHeader =  'public function get' . ucfirst($propName) . '(): ' . $propType->getType();

            $accessors[] = $accessorHeader . ($abstract ? ';' : '
    {
        return $this->' . $propName . ';
    }') ;
        }

        return trim(implode("\n\n    ", $accessors));
    }
}
