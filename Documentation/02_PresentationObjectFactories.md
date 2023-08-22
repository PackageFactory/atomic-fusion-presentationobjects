<div align="center">
    <a href="./01_PresentationObjectsAndComponents.md">&lt; 1. PresentationObjects and Components</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./03_Slots.md">3. Slots &gt;</a>
</div>

---

# 2. Content integration with PresentationObject Factories

> **Hint:** If you used the [Kickstarter](./Kickstarter.md) to create your component, an empty factory has already been created alongside the PresentationObject.

In this tutorial, we're going to write a PresentationObject factory for the image component we've created in "[PresentationObjects and Components](./PresentationObjectsAndComponents.md)". Let's assume that we have a `Vendor.Site:Content.Image` node type with the properties `image__src`, `image__alt` and `image__title` that we want to integrate with our Image component.

## Writing the factory

PresentationObject factories are co-located with their respective PresentationObjects. It's recommended to create factory methods with a speaking name prefixed with `for*` or `from*` to describe their use-case. When your code base grows, the factory will act like an index showing you all the different places in which the respective component is used.

<small>*`EXAMPLE: PresentationObject Factory`*</small>

```php
<?php

declare(strict_types=1);

namespace Vendor\Site\Presentation\Image;

use Neos\ContentRepository\Domain\Projection\Content\NodeInterface;
use Neos\Flow\Annotations as Flow;

#[Flow\Scope("singleton")]
final class ImageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forImageNode(NodeInterface $node): Image
    {
        // Optional: Use assertions to ensure the incoming node type
        assert($node->getNodeType()->isOfType('Vendor.Site:Content.Image'));

        return new Image(
            $node->getProperty('image__src')
                ? $this->uriService->getAssetUri($node->getProperty('image__src'))
                : $this->uriService->getDummyImageUri()
            $node->getProperty('image__alt') ?? '',
            $node->getProperty('image__title')
        );
    }
}
```

## Registering the factory

Each factory that extends `AbstractComponentPresentationObjectFactory` automatically implements the `Neos\Eel\ProtectedContextAwareInterface` and can be used as an Eel helper. To make our factory available in Fusion, we need to register it in the Settings:

<small>*`EXAMPLE: Settings.PresentationHelpers.yaml`*</small>

```yaml
Neos:
  Fusion:
    defaultContext:
      Vendor.Site.Image: Vendor\Site\Presentation\Image\ImageFactory
```

## Connecting the factory to content element rendering

Neos uses the `Neos.Neos:ContentCase` to map nodes to rendering prototypes. For our `Vendor.Site:Content.Image` node, the entry point is going to be a Fusion prototype of the same name.

From here, we just need to extend `Neos.Neos:ContentComponent` and provide our PresentationObject component `Vendor.Site:Component.Image` as the renderer. As the `presentationObject` we pass the result of the `forImageNode`-method of our newly registered `ImageFactory`.

<small>*`EXAMPLE: Resources/Private/Fusion/Integration/Content/Image.fusion`*</small>

```fusion
prototype(Vendor.Site:Content.Image) < prototype(Neos.Neos:ContentComponent) {
    renderer = Vendor.Site:Component.Image {
        presentationObject = ${Vendor.Site.Image.forImageNode(node)}
    }
}
```

That's it! Our image component is now fully integrated and can be edited in the Neos Backend.

---

<div align="center">
    <a href="./01_PresentationObjectsAndComponents.md">&lt; 1. PresentationObjects and Components</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./03_Slots.md">3. Slots &gt;</a>
</div>
