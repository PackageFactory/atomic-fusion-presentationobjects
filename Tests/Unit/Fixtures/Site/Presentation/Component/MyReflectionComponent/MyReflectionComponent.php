<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyReflectionComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponentInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponents;
use Vendor\Site\Presentation\Component\MyNewComponent\MyStringPseudoEnum;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

/**
 * Dummy component for test purposes
 * @Flow\Proxy(false)
 */
final class MyReflectionComponent extends AbstractComponentPresentationObject
{
    private string $stringProp;

    private ?string $nullableStringProp;

    private int $intProp;

    private ?int $nullableIntProp;

    private float $floatProp;

    private ?float $nullableFloatProp;

    private bool $boolProp;

    private ?bool $nullableBoolProp;

    private UriInterface $uriProp;

    private ?UriInterface $nullableUriProp;

    private ImageSourceHelperInterface $imageSourceProp;

    private ?ImageSourceHelperInterface $nullableImageSourceProp;

    private MyStringPseudoEnum $enumProp;

    private ?MyStringPseudoEnum $nullableEnumProp;

    private AnotherComponentInterface $componentProp;

    private ?AnotherComponentInterface $nullableComponentProp;

    private AnotherComponents $componentArrayProp;

    private \DateTimeImmutable $dateProp;
}
