<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>

---

# 1. PresentationObjects and Components

> **Hint:** This section describes the manual creation of PresentationObjects and PresentationObject components. Both patterns can also be scaffolded by the [Kickstarter](./04_Kickstarter.md).

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
    /**
     * @return string
     */
    public function getSrc(): string;

    /**
     * @return string
     */
    public function getAlt(): string;

    /**
     * @return null|string
     */
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
    /**
     * @var string
     */
    private $src;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var null|string
     */
    private $title;

    /**
     * @param string $src,
     * @param string $alt,
     * @param null|string $title
     */
    public function __construct(
        string $src,
        string $alt,
        ?string $title
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
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

<small>*`EXAMPLE: Resources/Private/Fusion/Presentation/Leaf/Image/Image.fusion`*<small>

```fusion
prototype(Vendor.Site:Leaf.Image) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
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

That's it! Our `Vendor.Site:Leaf.Image` can now be used like this (AFX):

```afx
myImage = afx`<Vendor.Site:Leaf.Image presentationObject={someObject}/>`
```

Or like this (Plain Fusion):

```fusion
myImage = Vendor.Site:Leaf.Image {
    presentationObject = ${someObject}
}
```

An exception will be thrown, if `someObject` does not implement `Vendor\Site\Presentation\Image\ImageInterface`.

---

<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>