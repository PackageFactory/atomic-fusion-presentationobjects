<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

use Neos\Eel\Context;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

trait PathEvaluation
{
    protected function evaluatePath(mixed $path, AbstractComponentPresentationObject $value): mixed
    {
        if ($path instanceof Context) {
            $path = $path->unwrap();
        }
        $path = (string)$path;
        if ($path === 'prototypeName') {
            return $value->getPrototypeName();
        }
        if (property_exists($value, $path)) {
            return $value->$path;
        }
        throw new \BadMethodCallException(
            '"' . $path . '" is not part of the component API for ' . get_class($value)
                . '. Please check your Fusion presentation component for typos.',
            1578905708
        );
    }

    protected function evaluateEnumPath(mixed $path, \BackedEnum $enum): int|string|bool
    {
        if ($path instanceof Context) {
            $path = $path->unwrap();
        }
        $path = (string)$path;
        if (property_exists($enum, $path)) {
            return $enum->$path;
        }
        if (method_exists($enum, $path)) {
            return $enum->$path();
        }
        throw new \BadMethodCallException(
            '"' . $path . '" is not part of the component API for enum ' . get_class($enum)
            . '. Please check your Fusion presentation component for typos.',
            1656808154
        );
    }
}
