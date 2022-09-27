<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

use Neos\Eel\Context;
use Neos\Eel\ProtectedContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

final class PresentationObjectAwareProtectedContext extends ProtectedContext
{
    use PathEvaluation;

    public static function fromContext(ProtectedContext $context): self
    {
        $presentationObjectAwareContext = new self($context->value);
        $presentationObjectAwareContext->allowedMethods = $context->allowedMethods;

        return $presentationObjectAwareContext;
    }

    /**
     * @param string|int|Context $path
     * @return mixed
     */
    public function get(mixed $path): mixed
    {
        if ($this->value instanceof AbstractComponentPresentationObject) {
            return $this->evaluatePath($path, $this->value);
        } elseif ($this->value instanceof \BackedEnum) {
            return $this->evaluateEnumPath($path, $this->value);
        } else {
            return parent::get($path);
        }
    }
}
