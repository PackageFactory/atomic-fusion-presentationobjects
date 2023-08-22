<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

use Neos\Flow\Annotations as Flow;

/**
 * The exception to be thrown if props cannot be deserialized
 */
#[Flow\Proxy(false)]
final class PropsCannotBeDeserialized extends \InvalidArgumentException
{
    public static function becauseTheyAreNoColonList(string $attemptedValue): self
    {
        return new self(
            'Given prop "' . $attemptedValue . '" cannot be deserialized, must be formatted as name:type.',
            1616459756
        );
    }
}
