<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyReflectionComponent;

use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponent;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponents;
use Vendor\Site\Presentation\Component\MyNewComponent\MyStringEnum;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

/**
 * Dummy component for test purposes
 */
#[Flow\Proxy(false)]
final class MyReflectionComponent extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $stringProp,
        public readonly ?string $nullableStringProp,
        public readonly int $intProp,
        public readonly ?int $nullableIntProp,
        public readonly float $floatProp,
        public readonly ?float $nullableFloatProp,
        public readonly bool $boolProp,
        public readonly ?bool $nullableBoolProp,
        public readonly UriInterface $uriProp,
        public readonly ?UriInterface $nullableUriProp,
        public readonly ImageSourceInterface $imageSourceProp,
        public readonly ?ImageSourceInterface $nullableImageSourceProp,
        public readonly SlotInterface $slotProp,
        public readonly ?SlotInterface $nullableSlotProp,
        public readonly MyStringEnum $enumProp,
        public readonly ?MyStringEnum $nullableEnumProp,
        public readonly AnotherComponent $componentProp,
        public readonly ?AnotherComponent $nullableComponentProp,
        public readonly AnotherComponents $componentArrayProp,
        public readonly \DateTimeImmutable $dateProp
    ) {
    }
}
