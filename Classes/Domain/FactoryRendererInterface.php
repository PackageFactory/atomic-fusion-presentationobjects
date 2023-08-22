<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Component;

interface FactoryRendererInterface
{
    public function renderFactoryContent(Component $component): string;
}
