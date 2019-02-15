<?php

namespace PackageFactory\AtomicFusion\DataObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.DataObjects package
 */

/**
 * A custom component implementation allowing the usage of component props implementations
 */
class DataObjectComponentImplementation extends \Neos\Fusion\FusionObjects\ComponentImplementation
{
    /**
     * Prepare the context for the renderer
     *
     * @param array $context
     * @return array
     */
    protected function prepare($context)
    {
        $context['dataObject'] = $this->getDataObject();

        return parent::prepare($context);
    }

    protected function getDataObject(): ComponentDataObjectInterface
    {
        $dataObject = $this->fusionValue('dataObject');

        if (is_null($dataObject)) {
            throw new ComponentDataObjectIsMissing('Component data object is missing, set it via dataObject = ... .');
        }

        $dataObjectInterface = $this->fusionValue('__meta/dataObjectInterface');
        if (is_null($dataObjectInterface)) {
            throw new ComponentDataObjectInterfaceIsUndeclared('Component data object is undeclared, set it via @dataObjectInterface = \'...\'.');
        }
        if (!interface_exists($dataObjectInterface)) {
            throw new ComponentDataObjectInterfaceIsMissing('Declared data object interface "' . $dataObjectInterface . '" is missing, please add it to your codebase.');
        }
        if (!$dataObject instanceof $dataObjectInterface) {
            throw new ComponentDataObjectDoesNotImplementRequiredInterface('Data object does not implement required ' . $dataObjectInterface . '.');
        }
        if (!$dataObject instanceof ComponentDataObjectInterface) {
            throw new ComponentDataObjectDoesNotImplementRequiredInterface('Data object does not implement required ' . ComponentDataObjectInterface::class . '.');
        }

        return $dataObject;
    }
}
