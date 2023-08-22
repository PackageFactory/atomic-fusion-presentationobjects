<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

use Neos\Eel\Context;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

final class PresentationObjectAwareContext extends Context
{
    use PathEvaluation;

    public static function fromContext(Context $context): self
    {
        return new self($context->value);
    }

    /**
     * @param string|int|Context $path
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
