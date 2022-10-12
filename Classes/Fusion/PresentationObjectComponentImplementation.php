<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use Neos\Fusion\FusionObjects\DataStructureImplementation;

/**
 * A custom component implementation allowing the usage of presentation objects in the fusion runtime
 */
class PresentationObjectComponentImplementation extends DataStructureImplementation
{
    const OBJECT_NAME = 'presentationObject';

    const INTERFACE_DECLARATION_NAME = '__meta/presentationObjectInterface';

    /**
     * Properties that are ignored and not included into the ``props`` context
     *
     * @var array|string[]
     */
    protected $ignoreProperties = ['__meta', 'renderer'];

    /**
     * Evaluate the fusion-keys and transfer the result into the context as ``props``
     * afterwards evaluate the ``renderer`` with this context
     *
     * @return mixed
     */
    public function evaluate()
    {
        $context = $this->runtime->getCurrentContext();
        $renderContext = $this->prepare($context);
        $result = $this->render($renderContext);

        return $result;
    }

    /**
     * Prepare the context for the renderer
     *
     * @phpstan-param array<string,mixed> $context
     * @param array $context
     * @phpstan-return array<string,mixed>
     * @return array
     */
    protected function prepare(array $context): array
    {
        $context[self::OBJECT_NAME] = $this->getPresentationObject();
        $context['props'] = $this->getProps();

        return $context;
    }

    /**
     * Calculate the component props
     *
     * @phpstan-return array<string,mixed>
     * @return array
     */
    protected function getProps()
    {
        /** @phpstan-var string[] $sortedChildFusionKeys */
        $sortedChildFusionKeys = $this->preparePropertyKeys($this->properties, $this->ignoreProperties);
        $props = [];
        foreach ($sortedChildFusionKeys as $key) {
            try {
                $props[$key] = $this->fusionValue($key);
            } catch (\Exception $e) {
                $props[$key] = $this->runtime->handleRenderingException($this->path . '/' . $key, $e);
            }
        }

        return $props;
    }

    /**
     * Evaluate the renderer with the give context and return
     *
     * @phpstan-param array<string,mixed> $context
     * @param array $context
     * @return mixed
     */
    protected function render(array $context)
    {
        $this->runtime->pushContextArray($context);
        $result = $this->runtime->render($this->path . '/renderer');
        $this->runtime->popContext();

        return $result;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return ComponentPresentationObjectInterface
     */
    protected function getPresentationObject(): ComponentPresentationObjectInterface
    {
        $presentationObject = $this->fusionValue(self::OBJECT_NAME);

        if (is_null($presentationObject)) {
            throw new ComponentPresentationObjectIsMissing('Component presentation object is missing, set it via presentationObject = ... .');
        }

        $presentationObjectInterface = $this->fusionValue(self::INTERFACE_DECLARATION_NAME);
        if (is_null($presentationObjectInterface)) {
            throw ComponentPresentationObjectInterfaceIsUndeclared::butWasSupposedTo();
        }
        if (!interface_exists($presentationObjectInterface) && !class_exists($presentationObjectInterface)) {
            throw ComponentPresentationObjectInterfaceIsMissing::butWasNotSupposedTo($presentationObjectInterface);
        }
        if (!$presentationObject instanceof $presentationObjectInterface) {
            throw ComponentPresentationObjectDoesNotImplementRequiredInterface::butWasSupposedTo($presentationObjectInterface);
        }
        if (!$presentationObject instanceof ComponentPresentationObjectInterface) {
            throw ComponentPresentationObjectDoesNotImplementRequiredInterface::butWasSupposedTo(ComponentPresentationObjectInterface::class);
        }

        return $presentationObject;
    }
}
