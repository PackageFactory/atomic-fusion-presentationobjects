<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

use Neos\Flow\Annotations as Flow;
use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;

#[Flow\Proxy(false)]
final readonly class ImageSourcePropType implements PropTypeInterface
{

    public function __construct(
        private bool $nullable
    ) {
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getSimpleName(): string
    {
        return 'ImageSourceInterface';
    }

    public function getUseStatement(): string
    {
        return 'use ' . ImageSourceInterface::class . ";\n";
    }

    public function getType(): string
    {
        return ($this->nullable ? '?' : '') . 'ImageSourceInterface';
    }

    public function getStyleGuideValue(int $nestingLevel = 0): string
    {
        return '= Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }';
    }

    public function getDefinitionData(string $propName): string
    {
        return '
                <Sitegeist.Kaleidoscope:Image imageSource={presentationObject.' . $propName . '}'
                . ($this->nullable ? ' @if={presentationObject.' . $propName . '}' : '') . ' />
            ';
    }
}
