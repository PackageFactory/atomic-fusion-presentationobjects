<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Component
{
    public function __construct(
        public ComponentName $name,
        public Props $props
    ) {
    }

    public function getClassContent(): string
    {
        return '<?php

' . $this->name->renderClassComment() . '

declare(strict_types=1);

namespace ' . $this->name->getPhpNamespace() . ';

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
' . $this->props->renderUseStatements() . '
#[Flow\Proxy(false)]
final readonly class ' . $this->name->getSimpleClassName() . ' extends AbstractComponentPresentationObject
{
    ' . $this->renderConstructor() .  '
}
';
    }

    public function getComponentArrayContent(): string
    {
        return '<?php

' . $this->name->renderClassComment() . '

declare(strict_types=1);

namespace ' . $this->name->getPhpNamespace() . ';

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<' . $this->name->getSimpleClassName() . '>
 */
#[Flow\Proxy(false)]
final readonly class ' . $this->name->getSimpleComponentArrayName() . ' implements \IteratorAggregate, \Countable
{
    /**
     * @var array<' . $this->name->getSimpleClassName() . '>
     */
    private array $' . $this->name->getSimpleComponentArrayPropertyName() . ';

    public function __construct(' . $this->name->getSimpleClassName() . ' ...$' . $this->name->getSimpleComponentArrayPropertyName() . ')
    {
        $this->' . $this->name->getSimpleComponentArrayPropertyName() . ' = $' . $this->name->getSimpleComponentArrayPropertyName() . ';
    }

    /**
     * @return \Iterator<' . $this->name->getSimpleClassName() . '>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->' . $this->name->getSimpleComponentArrayPropertyName() . ');
    }

    public function count(): int
    {
        return count($this->' . $this->name->getSimpleComponentArrayPropertyName() . ');
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
    @presentationObjectInterface = \'' . \str_replace('\\', '\\\\', $this->name->getFullyQualifiedClassName()) . '\'

    @styleguide {
        title = \'' . $this->name->name . '\'

        props {
' . $this->props->renderStyleGuideProps() . '
        }
    }

    renderer = afx`
        <div>[' . $this->name->name  . ']</div>
        <dl>
            ' . $this->props->renderDefinitionTerms() . '
        </dl>
    `
}
';
    }

    private function renderConstructor(): string
    {
        $arguments = [];
        foreach ($this->props as $propName => $propType) {
            $arguments[] = 'public ' . $propType->getType() . ' $' . $propName . ',';
        }
        return 'public function __construct(
        ' . trim(trim(implode("\n        ", $arguments)), ',') .  '
    ) {
    }';
    }
}
