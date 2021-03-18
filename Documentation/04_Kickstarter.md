<div align="center">
    <a href="./03_IntegrationRecipes.md">&lt; 3. Integration Recipes</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./05_PreviewMode.md">5. Preview Mode &gt;</a>
</div>

---

# Scaffolding with the Component Kickstarter

Due to the elaborate nature of PresentationObjects `PackageFactory.AtomicFusion.PresentationObjects` ships with a scaffolding tool that eases the creation of all required code patterns. This tool comes in the form of a set of Neos.Flow commands and enables you to generate code from the command line.

## `component:kickstartvalue` command

This command generates a new pseudo-enum value object. A pseudo-enum is an attempt to enable enumeration types in PHP, since it doesn't have a native language construct for this (although this might change in the future: https://wiki.php.net/rfc/enum).

Enumerations (or: enums) can be used to represent discrete values. Think of the state of a traffic light which can only take one of the values red, yellow and green (simplified, of course). A good example in HTML would be the `type` attribute of a `<button>` element, which is supposed to only take one of the values `button` or `submit`.

For more information on this pattern, have a look at this excellent article: https://stitcher.io/blog/php-enums

> **Hint:** For a full parameter list use the built-in command documentation of Neos.Flow: `./flow help component:kickstartvalue`

### Example

```sh
./flow component:kickstartvalue --package-key=Vendor.Site \
    Headline \
    HeadlineLook string \
        --values=REGULAR,HERO
```

### What files are being created?

#### HeadlineLook.php

This is the central pseudo-enum class. It consists of:

* A set of constants that represent the enum values
* Static factory methods that are named like the enum values
* `getIs*` methods to identify a value both in PHP and Neos.Eel
* A static `getValues` method to retrieve a list of all possible enum values
* A `getValue` method that will return the value of the enum instance as your chosen type
* A `__toString` method for string casting

```php
<?php
namespace Vendor\Site\Presentation\Headline;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class HeadlineLook
{
    const LOOK_REGULAR = 'REGULAR';
    const LOOK_HERO = 'HERO';

    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $string): self
    {
        if (!in_array($string, self::getValues())) {
            throw HeadlineLookIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
        }

        return new self($string);
    }

    public static function regular(): self
    {
        return new self(self::LOOK_REGULAR);
    }

    public static function hero(): self
    {
        return new self(self::LOOK_HERO);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::LOOK_REGULAR;
    }

    public function getIsHero(): bool
    {
        return $this->value === self::LOOK_HERO;
    }

    /**
     * @return array|string[]
     */
    public static function getValues(): array
    {
        return [
            self::LOOK_REGULAR,
            self::LOOK_HERO
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

#### HeadlineLookIsInvalid.php

The exception in this file will be thrown, when the pseudo-enum is initialized with an invalid value (which could happen, when `::fromString` is called).

```php
<?php
namespace Vendor\Site\Presentation\Headline;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class HeadlineLookIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self('The given value "' . $attemptedValue . '" is no valid HeadlineLook, must be one of the defined constants. ', 1602423895);
    }
}
```

#### HeadlineLookProvider.php

This file contains a data provider to use the enum values for SelectBoxes in the Neos backend UI.

```php
<?php
namespace Vendor\Site\Application;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use Vendor\Site\Presentation\Headline\HeadlineLook;

class HeadlineLookProvider extends AbstractDataSource implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = 'vendor-site-headline-looks';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $headlineLooks = [];
        foreach (HeadlineLook::getValues() as $value) {
            $headlineLooks[$value]['label'] = $this->translator->translateById('headlineLook.' . $value, [], null, null, 'Headline', 'Vendor.Site') ?: $value;
        }

        return $headlineLooks;
    }

    /**
     * @return array|string[]
     */
    public function getValues(): array
    {
        return HeadlineLook::getValues();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
```

This data source can be used in your `NodeTypes.*.yaml` configuration like this:

```yaml
  properties:
    headlineLook:
      type: string
      ui:
        label: 'Headline look'
        reloadIfChanged: true
        inspector:
          group: 'headline'
          editor: Neos.Neos/Inspector/Editors/SelectBoxEditor
          editorOptions:
            placeholder: Choose a headline look...
            dataSourceIdentifier: vendor-site-headline-looks
```

## `component:kickstart` command

This command creates all patterns needed for a component. It takes the name of the component and a list of property descriptors which consist of a property name and a type name separated by a colon.
The package the component resides in can be set via --package-key, otherwise it will be fetched from the configuration option `PackageFactory.AtomicFusion.PresentationObjects.componentGeneration:defaultPackageKey` with fallback to the primary site package. 
Via --namespace, the component's Fusion namespace can be defined. It can be segmented with . and defaults to `Component` A component `Headline` in package `Vendor.Site` and namespace `Component.Atom` will have the name `Vendor.Site:Component.Atom.Headline` and be placed in the folder `Vendor.Site/Resources/Private/Fusion/Component/Atom/Headline`

For type names, the following rules apply:

* Any scalar type (`string`, `int`, `float`, `bool`) will be treated as-is
* Prefixing a type with `?` will make it nullable in PHP (https://wiki.php.net/rfc/nullable_types)
* `ImageSource` will create a `use`-statement for the `ImageSourceHelperInterface` from [Sitegeist.Kaleidoscope](https://github.com/sitegeist/Sitegeist.Kaleidoscope), as well as a propery styleguide example with the `Sitegeist.Kaleidoscope:DummyImageSource` prototype
* `Uri` will create a `use`-statement for the `Psr\Http\Message\UriInterface`

### Example

> **Hint:** It is recommended to create all required values and sub-components beforehand, so the kickstarter can find and create proper `use`-statements for them.

```sh
./flow component:kickstart --package-key=Vendor.Site --namespace=Component
    Headline \
        type:HeadlineType \
        look:HeadlineLook \
        content:string
```

### What files are being created?

#### Headline.fusion

The is the fusion code for the component. It consists of a full `@styleguide` configuration for [Sitegeist.Monocle](https://github.com/sitegeist/Sitegeist.Monocle) as well as a dummy renderer that displays all PresentationObject properties as a definition list (see: https://developer.mozilla.org/de/docs/Web/HTML/Element/dl).

```fusion
prototype(Vendor.Site:Leaf.Headline) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Headline\\HeadlineInterface'

    @styleguide {
        title = 'Headline'

        props {
            type = ''
            look = ''
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

#### HeadlineInterface.php

This is the PHP interface of the PresentationObject. It consists of a getter for each property descriptor that was passed to `component:kickstart`.

```php
<?php
namespace Vendor\Site\Presentation\Headline;

/*
 * This file is part of the Vendor.Site package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface HeadlineInterface extends ComponentPresentationObjectInterface
{
    public function getType(): HeadlineType;

    public function getLook(): HeadlineLook;

    public function getContent(): string;
}
```

#### Headline.php

This is the PresentationObject itself. It is a full implementation of the interface from above.

```php
<?php
namespace Vendor\Site\Presentation\Headline;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class Headline extends AbstractComponentPresentationObject implements HeadlineInterface
{
    /**
     * @var HeadlineType
     */
    private $type;

    /**
     * @var HeadlineLook
     */
    private $look;

    /**
     * @var string
     */
    private $content;

    public function __construct(
        HeadlineType $type,
        HeadlineLook $look,
        string $content
    ) {
        $this->type = $type;
        $this->look = $look;
        $this->content = $content;
    }

    public function getType(): HeadlineType
    {
        return $this->type;
    }

    public function getLook(): HeadlineLook
    {
        return $this->look;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
```

#### HeadlineFactory.php

This is an empty factory for the PresentationObject that is supposed to be used for content integration.

```php
<?php
namespace Vendor\Site\Presentation\Headline;

/*
 * This file is part of the Vendor.Site package.
 */

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

---

<div align="center">
    <a href="./03_IntegrationRecipes.md">&lt; 3. Integration Recipes</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./05_PreviewMode.md">5. Preview Mode &gt;</a>
</div>