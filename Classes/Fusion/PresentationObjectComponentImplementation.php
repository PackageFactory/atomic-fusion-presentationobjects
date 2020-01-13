<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * A custom component implementation allowing the usage of presentation objects in the fusion runtime
 */
class PresentationObjectComponentImplementation extends \Neos\Fusion\FusionObjects\ComponentImplementation
{
    const PREVIEW_MODE = 'isInPreviewMode';

    const OBJECT_NAME = 'presentationObject';

    const INTERFACE_DECLARATION_NAME = '__meta/presentationObjectInterface';

    /**
     * Prepare the context for the renderer
     *
     * @param array $context
     * @return array
     */
    protected function prepare($context)
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

        return parent::prepare($context);
    }

    protected function isInPreviewMode(): bool
    {
        return $this->fusionValue(self::PREVIEW_MODE);
    }

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
     *
     * @return string
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
