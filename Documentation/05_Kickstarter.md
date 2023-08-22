<div align="center">
    <a href="./04_IntegrationRecipes.md">&lt; 4. Integration Recipes</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./06_PreviewMode.md">6. Preview Mode &gt;</a>
</div>

---

# 5. Scaffolding with the Component Kickstarter

Due to the elaborate nature of PresentationObjects `PackageFactory.AtomicFusion.PresentationObjects` ships with a scaffolding tool that eases the creation of all required code patterns. This tool comes in the form of a set of Neos.Flow commands and enables you to generate code from the command line.

## `component:kickstartenum` command

This command generates a new pseudo-enum value object. A pseudo-enum is an attempt to enable enumeration types in PHP, since it doesn't have a native language construct for this (although this might change in the future: https://wiki.php.net/rfc/enum).

Enumerations (or: enums) can be used to represent discrete values. Think of the state of a traffic light which can only take one of the values red, yellow and green (simplified, of course). A good example in HTML would be the `type` attribute of a `<button>` element, which is supposed to only take one of the values `button` or `submit`.

For more information on this pattern, have a look at this excellent article: https://stitcher.io/blog/php-enums

> **Hint:** For a full parameter list use the built-in command documentation of Neos.Flow: `./flow help component:kickstartenum`

### Example

```sh
./flow component:kickstartenum \
    Vendor.Site:Headline \
    HeadlineLook string \
        --values=regular,hero
```

> **Hint:** Components are namespaced, defaulting to "Component". The component name "Vendor.Site:Headline" thus will be evaluated as "Vendor.Site:Component.Headline".
> Arbitrary other namespaces, including nested, are supported, like "Vendor.Site:MyNamespace.Headline" or "Vendor.Site:My.Namespace.Headline".

### What files are being created?

#### HeadlineLook.php

This is the central enum. Additional to PHP's core enum functions, it consists of `getIs*` methods to identify a value both in PHP and Neos.Eel

```php
<?php

/*
 * This file is part of the Vendor.Site package.
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Headline;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HeadlineLook:string
{
    use StringComponentVariant

    case LOOK_REGULAR = 'regular';
    case LOOK_HERO = 'hero';
}
```

## `component:kickstart` command

This command creates all patterns needed for a component. It takes the name of the component and a list of property descriptors which consist of a property name and a type name separated by a colon.
The package the component resides in can be set via --package-key, otherwise it will be fetched from the configuration option `PackageFactory.AtomicFusion.PresentationObjects.componentGeneration:defaultPackageKey` with fallback to the primary site package.
Via --namespace, the component's Fusion namespace can be defined. It can be segmented with . and defaults to `Component` A component `Headline` in package `Vendor.Site` and namespace `Component.Atom` will have the name `Vendor.Site:Component.Atom.Headline` and be placed in the folder `Vendor.Site/Resources/Private/Fusion/Component/Atom/Headline`

For type names, the following rules apply:

* Any scalar type (`string`, `int`, `float`, `bool`) and enums will be treated as-is
* Prefixing a type with `?` will make it nullable in PHP (https://wiki.php.net/rfc/nullable_types)
* `ImageSource` will create a `use`-statement for the `ImageSourceInterface` from [Sitegeist.Kaleidoscope](https://github.com/sitegeist/Sitegeist.Kaleidoscope), as well as a propery styleguide example with the `Sitegeist.Kaleidoscope:DummyImageSource` prototype
* `Uri` will create a `use`-statement for the `Psr\Http\Message\UriInterface`
* `slot` will create a `use`-statement for the `SlotInterface`

### Example

> **Hint:** It is recommended to create all required enums and sub-components beforehand, so the kickstarter can find and create proper `use`-statements for them.

```sh
./flow component:kickstart Vendor.Site:Headline \
        type:HeadlineType \
        look:HeadlineLook \
        content:string
```

> **Hint:** Components are namespaced, defaulting to "Component". The component name "Vendor.Site:Headline" thus will be evaluated as "Vendor.Site:Component.Headline".
> Arbitrary other namespaces, including nested, are supported, like "Vendor.Site:MyNamespace.Headline" or "Vendor.Site:My.Namespace.Headline".

### What files are being created?

#### Headline.fusion

The is the fusion code for the component. It consists of a full `@styleguide` configuration for [Sitegeist.Monocle](https://github.com/sitegeist/Sitegeist.Monocle) as well as a dummy renderer that displays all PresentationObject properties as a definition list (see: https://developer.mozilla.org/de/docs/Web/HTML/Element/dl).

```fusion
prototype(Vendor.Site:Component.Headline) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Headline\\Headline'

    @styleguide {
        title = 'Headline'

        props {
            type = 'h1'
            look = 'regular'
            content = 'Text'
        }
    }

    renderer = afx`<dl>
        <dt>type:</dt>
        <dd>{presentationObject.type}</dd>
        <dt>look:</dt>
        <dd>{presentationObject.look}</dd>
        <dt>content:</dt>
        <dd>{presentationObject.content}</dd>
    </dl>`
}
```

#### Headline.php

This is the PresentationObject itself.

```php
<?php

/*
 * This file is part of the Vendor.Site package.
 */
 
declare(strict_types=1);
 
namespace Vendor\Site\Presentation\Component\Headline;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

 #[Flow\Proxy(false)]
final readonly class Headline extends AbstractComponentPresentationObject
{
    public function __construct(
        public HeadlineType $type,
        public HeadlineLook $look,
        public string $content
    ) {
    }
}
```

#### HeadlineFactory.php

This is an empty factory for the PresentationObject that is supposed to be used for content integration.

```php
<?php

/*
 * This file is part of the Vendor.Site package.
 */
 
declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\Headline;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class HeadlineFactory extends AbstractComponentPresentationObjectFactory
{
}
```

#### Settings.PresentationHelpers.yaml

The PresentationObject factory from above is automatically registered for the default Fusion Eel context. The name of the Eel-Helper consists of the package key (without the vendor namespace) and the PresentationObject name.

```yaml
Neos:
  Fusion:
    defaultContext:
      Site.Headline: Vendor\Site\Presentation\Headline\HeadlineFactory
```


### Component file colocation

One great feature of Fusion components is that all files constituting this component are located in the same folder.
This does not work by default, since Flow packages' classes reside in `Classes`, while presentational components reside in `Resources/Private/Fusion/Presentation`.

To still achieve colocation, two parameters have to be adjusted:

#### composer.json

Composer's PSR-4 autoload section allows for multiple entries. We can use this as follows:

```json
  "autoload": {
    "psr-4": {
      "Vendor\\Site\\": "Classes/",
      "Vendor\\Site\\Presentation\\": "Resources/Private/Fusion/Presentation/"
    }
  }
```
This way, presentation objects, enums and factories placed in their component's Fusion folder are autoloaded as if they were located in the usual folders under `Classes`.

#### Settings

The component kickstarter can be configured to place the generated PHP files in the respective Fusion folders as follows:

```yaml
PackageFactory:
  AtomicFusion:
    PresentationObjects:
      componentGeneration:
        colocate: true
```

> **Hint:** It is highly recommended to decide on colocation once at the start of a project.

---

<div align="center">
    <a href="./04_IntegrationRecipes.md">&lt; 4. Integration Recipes</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./06_PreviewMode.md">6. Preview Mode &gt;</a>
</div>
