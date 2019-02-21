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
    /**
     * Prepare the context for the renderer
     *
     * @param array $context
     * @return array
     */
    protected function prepare($context)
    {
        if ($this->fusionValue('isInPreviewMode')) {
            $context['presentationObject'] = $this->getProps();
        } else {
            $context['presentationObject'] = $this->getPresentationObject();
        }

        return parent::prepare($context);
    }

    protected function getPresentationObject(): ComponentPresentationObjectInterface
    {
        $presentationObject = $this->fusionValue('presentationObject');

        if (is_null($presentationObject)) {
            throw new ComponentPresentationObjectIsMissing('Component presentation object is missing, set it via presentationObject = ... .');
        }

        $presentationObjectInterface = $this->fusionValue('__meta/presentationObjectInterface');
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
}
