![CI](https://github.com/PackageFactory/atomic-fusion-presentationobjects/workflows/CI/badge.svg?branch=release-1.0)

# PackageFactory.AtomicFusion.PresentationObjects

> Allows for usage of type-safe, testable presentation objects (e.g. value objects) in Atomic Fusion as a replacement for props and propsets.

## Installation

```
composer require packagefactory/atomicfusion-presentationobjects
```

## Why

PackageFactory.AtomicFusion has been the first step in the direction of Component architecture in Neos CMS. It provided a `Component` fusion prototype that allowed for writing frontend components with a clear interface for the backend.

However, that interface hasn't been strict. Developers were able to express requirements for their components, but those requirements weren't enforced on any level.

Because of that, the concept of PropTypes were adopted from React.js in the form of PackageFactory.AtomicFusion.PropTypes. PropTypes check incoming data against a defined schema whenever a component is invoked at runtime, thus ensuring that a component can never be rendered with invalid data.

With the advent of Typescript PropTypes have become sort-of obsolete in the React world, since static typings do not have an impact on bundle size and catch type-related bugs before runtime.

TODO: DDD tactical pattern ValueObject

### Benefits

TODO

### Drawbacks

TODO

## Usage

### Writing a PresentationObject

PresentationObject are ValueObjects. In that they are immutable and can only consist of scalar properties or other value objects.

<small>*`EXAMPLE: PresentationObject`*<small>

```php
<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\MyPresentationObject;

/**
 * It's highly recommended to declare PresentationObjects as final to them
 * canonical.
 */
final class MyPresentationObject implements MyPresentationObjectInterface
{
    /**
     * @var string
     */
    private $firstProperty;

    /**
     * @var integer
     */
    private $secondProperty;

    /**
     * @param string $firstProperty
     * @param integer $secondProperty
     */
    public function __construct(
        string $firstProperty,
        int $secondProperty
    ) {
        $this->firstProperty = $firstProperty;
        $this->secondProperty = $secondProperty;
    }

    /**
     * @return string
     */
    public function getFirstProperty(): string
    {
        return $this->firstProperty;
    }

    /**
     * PresentationObjects are immutable. In order to perform change actions
     * you need to implement a copy-on-write mechanism like this one.
     *
     * Such with*-methods are optional however.
     *
     * @param string $firstProperty
     * @return self
     */
    public function withFirstProperty(string $firstProperty): self
    {
        return new self($firstProperty, $this->secondProperty);
    }

    /**
     * @return integer
     */
    public function getSecondProperty(): int
    {
        return $this->secondProperty;
    }
}
```

### Binding a PresentationObject to a PresentationObjectComponent

<small>*`EXAMPLE: PresentationObject Interface`*<small>

```php
<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\MyPresentationObject;

interface MyPresentationObjectInterface
{
    /**
     * @return string
     */
    public function getFirstProperty(): string;

    /**
     * @return integer
     */
    public function getSecondProperty(): int
}
```

<small>*`EXAMPLE: PresentationObject Component`*<small>

```fusion
prototype(Vendor.Site:MyPresentationObject) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\MyPresentationObject\\MyPresentationObjectInterface'

    renderer = afx`
        <dl>
            <dt>First property:</dt>
            <dd>{presentationObject.firstProperty}</dd>
            <dt>Second property:</dt>
            <dd>{presentationObject.secondProperty}</dd>
        </dl>
    `
}
```

TODO

### Writing and registering a PresentationObject Factory

<small>*`EXAMPLE: PresentationObject Factory`*<small>

```php
<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\MyPresentationObject;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

/**
 * @Flow\Scope("singleton")
 */
final class MyPresentationObjectFactory extends AbstractComponentPresentationObjectFactory
{
}
```

<small>*`EXAMPLE: Settings.PresentationHelpers.yaml`*<small>

```yaml
Neos:
  Fusion:
    defaultContext:
      Vendor.Site.MyPresentationObject: Vendor\Site\Presentation\MyPresentationObject\MyPresentationObjectFactory
```

TODO

### Neos CMS content integration with PresentationObject Factories

TODO

<small>*`EXAMPLE: MyContentElement.fusion`*<small>

```fusion
prototype(Vendor.Site:MyContentElement) < prototype(Neos.Neos:ContentComponent) {
    renderer = Vendor.Site:MyPresentationObject {
        presentationObject = ${Vendor.Site.MyPresentationObject.forNode(node)}
    }
}
```

<small>*`EXAMPLE: PresentationObject Factory`*<small>

```php
/* ... */
final class MyPresentationObjectFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @param TraversableNodeInterface $node
     * @return MyPresentationObjectInterface
     */
    public function forNode(TraversableNodeInterface $node): MyPresentationObjectInterface
    {
        return new MyPresentationObject(
            $node->getProperty('firstProperty'),
            $node->getProperty('secondProperty')
        );
    }
}
```

TODO: see Docs/Integration.md

### Using the code generator

TODO

```sh
./flow component:kickstartvalue --package-key=Vendor.Site \
    Headline \
    HeadlineLook string \
        --values=REGULAR,HERO
```

```sh
./flow component:kickstart --package-key=Vendor.Site
    Headline \
        content:string \
        look:HeadlineLook
```

### Preview Mode

The `PresentationObjectComponent` has a special flag to change its behavior when used with tools like Sitegeist.Monocle.

Sitegeist.Monocle uses dummy data that is read directly from an annotation within the component code. That data ends up being a plain PHP array, that does not implement the desired interface. The PresentationObject enforcement would thus break Sitegeist.Monocle's component preview.

When the flag `isInPreviewMode` ist set to `true`, the default `props` context
is folded into the `presentationObject` context and the PresentationObject enforcement is deactivated.

This allows seamless use with tools like Sitegeist.Monocle.

<small>*`EXAMPLE: Root.fusion`*<small>

```fusion
prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    isInPreviewMode = ${request.controllerPackageKey == 'Sitegeist.Monocle'}
}
```

> The above example shows how `isInPreviewMode` can be set to true for all PresentationObjectComponents that are rendered in Sitegeist.Monocle.

## License

see [LICENSE](./LICENSE)