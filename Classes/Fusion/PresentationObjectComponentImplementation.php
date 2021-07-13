<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Fusion\FusionObjects\DataStructureImplementation;

/**
 * A custom component implementation allowing the usage of presentation objects in the fusion runtime
 */
class PresentationObjectComponentImplementation extends DataStructureImplementation
{
    const PREVIEW_MODE = 'isInPreviewMode';

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
        if ($this->isInPreviewMode()) {
            $props = $this->getProps();
            if (isset($props[self::OBJECT_NAME])) {
                $props = array_merge($props, $props[self::OBJECT_NAME]);
                unset($props[self::OBJECT_NAME]);
            }
            $context[self::OBJECT_NAME] = $props;
        } else {
            $context[self::OBJECT_NAME] = $this->getPresentationObject();
        }

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
        $sortedChildFusionKeys = $this->sortNestedFusionKeys();
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
     * @return boolean
     */
    protected function isInPreviewMode(): bool
    {
        return $this->fusionValue(self::PREVIEW_MODE);
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
            throw new ComponentPresentationObjectInterfaceIsUndeclared('The component\'s presentation object interface is undeclared, set it via @presentationObjectInterface = \'...\'.');
        }
        if (!interface_exists($presentationObjectInterface) && !class_exists($presentationObjectInterface)) {
            throw new ComponentPresentationObjectInterfaceIsMissing('Declared presentation object interface "' . $presentationObjectInterface . '" is missing, please add it to your codebase.');
        }
        if (!$presentationObject instanceof $presentationObjectInterface) {
            throw new ComponentPresentationObjectDoesNotImplementRequiredInterface('Presentation object does not implement required ' . $presentationObjectInterface . '.');
        }
        if (!$presentationObject instanceof ComponentPresentationObjectInterface) {
            throw new ComponentPresentationObjectDoesNotImplementRequiredInterface('Presentation object does not implement required ' . ComponentPresentationObjectInterface::class . '.');
        }

        return $presentationObject;
    }

    /**
     * Returns the Fusion path to the to-be-wrapped Content Element, if applicable
     * (Borrowed from \Neos\Neos\Fusion\ContentElementWrappingImplementation)
     *
     * @TODO: We need to have a look at this one, it doesn't seem to be used anywhere (@WBE)
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getContentElementFusionPath(): string
    {
        $fusionPathSegments = explode('/', $this->path);
        $numberOfFusionPathSegments = count($fusionPathSegments);
        if (isset($fusionPathSegments[$numberOfFusionPathSegments - 3])
            && $fusionPathSegments[$numberOfFusionPathSegments - 3] === '__meta'
            && isset($fusionPathSegments[$numberOfFusionPathSegments - 2])
            && $fusionPathSegments[$numberOfFusionPathSegments - 2] === 'process') {
            // cut off the SHORT processing syntax "__meta/process/contentElementWrapping<Neos.Neos:ContentElementWrapping>"
            return implode('/', array_slice($fusionPathSegments, 0, -3));
        }

        if (isset($fusionPathSegments[$numberOfFusionPathSegments - 4])
            && $fusionPathSegments[$numberOfFusionPathSegments - 4] === '__meta'
            && isset($fusionPathSegments[$numberOfFusionPathSegments - 3])
            && $fusionPathSegments[$numberOfFusionPathSegments - 3] === 'process') {
            // cut off the LONG processing syntax "__meta/process/contentElementWrapping/expression<Neos.Neos:ContentElementWrapping>"
            return implode('/', array_slice($fusionPathSegments, 0, -4));
        }
        return $this->path;
    }
}
