<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>

---

# 1. PresentationObjects and Components

> **Hint:** This section describes the manual creation of PresentationObjects, Pseudo-Enums and PresentationObject components. All of these patterns can also be scaffolded by the [Kickstarter](./05_Kickstarter.md).

In this tutorial, we're going to write a PresentationObject for an image component. Our image consists of a `src`, an `alt` and an optional `title` property.

## Writing the PresentationObject

The most important function of PresentationObjects is to enforce the interface between domain and presentation layer.

For a single component that interface is represented by an immutable PHP data transfer object. So let's start with that:

**Important:** PresentationObject are modeled close to value objects in that they are immutable and can only consist of scalar properties, other presentation objects or arrays of the former two.

<small>*`EXAMPLE: PresentationObject`*</small>

```php
<?php
 
declare(strict_types=1);

namespace Vendor\Site\Presentation\Image;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * Tip:
 * It's highly recommended to declare PresentationObjects as final to
 * keep them canonical.
 */
final class Image extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $src,
        public readonly string $alt,
        public readonly ?string $title
    ) {
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
}
```

## Writing the PresentationObject component

For our component we need to extend `PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent`, which works similarly to `Neos.Fusion:Component`.

The first difference to `Neos.Fusion:Component` is the mandatory `@presentationObjectInterface` annotation which connects our component to the PHP interface from above.

The second difference is, that besides the usual `props`-Context, your renderer can now also access the special `presentationObject`-Context, which holds our verified data.

<small>*`EXAMPLE: Resources/Private/Fusion/Presentation/Component/Image/Image.fusion`*</small>

```fusion
prototype(Vendor.Site:Component.Image) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Image\\Image'

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

An exception will be thrown, if `someObject` does not implement `Vendor\Site\Presentation\Image\Image`.

## Enums

It is recommended to model discrete values for presentation object properties as objects themselves.
Since as of version 8.1 PHP does now support enums, this package provides full enum support
> **Hint:** For more information on enums in PHP, see https://stitcher.io/blog/php-enums

Given we have a presentation object Headline with properties type and content.
While content can be an arbitrary string, in our project by specification we only support h1-h3 as types for headlines.
To prevent accidental use of h4 (or other tag names) for headlines, we model the headline type as an enum as follows:

<small>*`EXAMPLE: enum`*</small>

```php
<?php

declare(strict_types=1);

namespace Acme\Site\Presentation\Block\Headline;

enum HeadlineType:string
{
    case TYPE_H1 = 'h1';
    case TYPE_H2 = 'h2';
    case TYPE_H3 = 'h3';

    public function getIsH1(): bool
    {
        return $this === self::TYPE_H1;
    }

    public function getIsH2(): bool
    {
        return $this === self::TYPE_H2;
    }

    public function getIsH3(): bool
    {
        return $this === self::TYPE_H3;
    }
}
```

Now instead of having a string typed property in our headline, we can model it as follows:

```php
<?php
 
declare(strict_types=1);

namespace Acme\Site\Presentation\Block\Headline;

final class Headline
{
    public function __construct(
        public readonly HeadlineType $headlineType,
        public readonly string $content
    ) {
    }
}
```

> **Hint:** Enums can also be used in Neos' inspector select box editor. Please refer to [Integration Recipes](./04_IntegrationRecipes.md) to learn how it is done.


---

<div align="center">
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./02_PresentationObjectFactories.md">2. Content integration with PresentationObject Factories &gt;</a>
</div>
