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
final readonly class MyReflectionComponent extends AbstractComponentPresentationObject
{
    public function __construct(
        public string $stringProp,
        public ?string $nullableStringProp,
        public int $intProp,
        public ?int $nullableIntProp,
        public float $floatProp,
        public ?float $nullableFloatProp,
        public bool $boolProp,
        public ?bool $nullableBoolProp,
        public UriInterface $uriProp,
        public ?UriInterface $nullableUriProp,
        public ImageSourceInterface $imageSourceProp,
        public ?ImageSourceInterface $nullableImageSourceProp,
        public SlotInterface $slotProp,
        public ?SlotInterface $nullableSlotProp,
        public MyStringEnum $enumProp,
        public ?MyStringEnum $nullableEnumProp,
        public AnotherComponent $componentProp,
        public ?AnotherComponent $nullableComponentProp,
        public AnotherComponents $componentArrayProp,
        public \DateTimeImmutable $dateProp
    ) {
    }
}
