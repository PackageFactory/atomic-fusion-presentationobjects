<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>

---

# 1. PresentationObjects and Components

> **Hint:** This section describes the manual creation of PresentationObjects, Pseudo-Enums and PresentationObject components. All these patterns can also be scaffolded by the [Kickstarter](./05_Kickstarter.md).

In this tutorial, we're going to write a PresentationObject for an image component. Our image consists of a `src`, an `alt` and an optional `title` property.

## Writing the PresentationObject

The most important function of PresentationObjects is to enforce the interface between domain and presentation layer.

For a single component that interface is represented by an actual PHP interface. So let's start with that:

<small>*`EXAMPLE: PresentationObject Interface`*<small>

```php
<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Image;

interface ImageInterface
{
    public function getSrc(): string;

    public function getAlt(): string;

    public function getTitle(): ?string;
}
```

Next, we're going to write the actual object implementing the Interface from above.

**Important:** PresentationObject are ValueObjects. In that they are immutable and can only consist of scalar properties, other value objects or arrays of the former two.

<small>*`EXAMPLE: PresentationObject`*<small>

```php
<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Image;

/**
 * Tip:
 * It's highly recommended to declare PresentationObjects as final to
 * keep them canonical.
 */
final class Image implements ImageInterface
{
    private string $src;

    private string $alt;

    private ?string $title;

    public function __construct(
        string $src,
        string $alt,
        ?string $title
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->title = $title;
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * Tip:
     * PresentationObjects are immutable. In order to perform change actions
     * you need to implement a copy-on-write mechanism like this one.
     *
     * Such with*-methods are optional however.
     *
     * @param string $alt
     * @return self
     */
    public function withAlt(string $alt): self
    {
        return new self($this->src, $alt, $this->title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
```

## Writing the PresentationObject component

For our component we need to extend `PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent`, which works similarly to `Neos.Fusion:Component`.

The first difference to `Neos.Fusion:Component` is the mandatory `@presentationObjectInterface` annotation which connects our component to the PHP interface from above.

The second difference is, that besides the usual `props`-Context, your renderer can now also access the special `presentationObject`-Context, which holds our verified data.

<small>*`EXAMPLE: Resources/Private/Fusion/Presentation/Component/Image/Image.fusion`*<small>

```fusion
prototype(Vendor.Site:Component.Image) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Image\\ImageInterface'

    renderer = afx`
        <img
            src={presentationObject.src}
            alt={presentationObject.alt}
            title={presentationObject.title}
            />
    `
}
```

That's it! Our `Vendor.Site:Component.Image` can now be used like this (AFX):

```afx
myImage = afx`<Vendor.Site:Component.Image presentationObject={someObject}/>`
```

Or like this (Plain Fusion):

```fusion
myImage = Vendor.Site:Component.Image {
    presentationObject = ${someObject}
}
```

An exception will be thrown, if `someObject` does not implement `Vendor\Site\Presentation\Image\ImageInterface`.

## (Pseudo) Enums

It is recommended to model discrete values for presentation object properties as objects themselves.
Since PHP does not support enums yet, this package provides an interface to be implemented by classes that behave similarly to enums.
> **Hint:** For more information on enums in PHP, see https://stitcher.io/blog/php-enums

<small>*`EXAMPLE: Pseudo-enum`*<small>

Given we have a presentation object Headline with properties type and content.
While content can be an arbitrary string, in our project by specification we only support h1-h3 as types for headlines.
To prevent accidental use of h4 (or other tag names) for headlines, we model the headline type as a pseudo enum as follows:

```php
<?php declare(strict_types=1);
namespace Acme\Site\Presentation\Block\Headline;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class HeadlineType implements PseudoEnumInterface
{
    const TYPE_H1 = 'h1';
    const TYPE_H2 = 'h2';
    const TYPE_H3 = 'h3';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances = [];

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::TYPE_H1
                && $string !== self::TYPE_H2
                && $string !== self::TYPE_H3) {
                throw HeadlineTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function h1(): self
    {
        return self::from(self::TYPE_H1);
    }

    public static function h2(): self
    {
        return self::from(self::TYPE_H2);
    }

    public static function h3(): self
    {
        return self::from(self::TYPE_H3);
    }

    public function getIsH1(): bool
    {
        return $this->value === self::TYPE_H1;
    }

    public function getIsH2(): bool
    {
        return $this->value === self::TYPE_H2;
    }

    public function getIsH3(): bool
    {
        return $this->value === self::TYPE_H3;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TYPE_H1),
            self::from(self::TYPE_H2),
            self::from(self::TYPE_H3)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

Now instead of having a string typed property in our headline, we can model it as follows:

```php
<?php declare(strict_types=1);
namespace Acme\Site\Presentation\Block\Headline;

interface HeadlineInterface
{
    public function getType(): HeadlineType;

    public function getContent(): string;
}
```

> **Hint:** Pseudo-enums can also be used in Neos' inspector select box editor. Please refer to [Integration Recipes](./04_IntegrationRecipes.md) to learn how it is done.


---

<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>
