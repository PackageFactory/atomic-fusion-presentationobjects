<div align="center">
    <a href="./02_PresentationObjectFactories.md">&lt; 2. Content integration with PresentationObject Factories</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./04_Kickstarter.md">4. Kickstarter &gt;</a>
</div>

---

# 3. Integration Recipes

This article discusses various helper APIs you can use for integrating content via PresentationObject factories.

## Editable properties

In plain AtomicFusion we would use `Neos.Neos:Editable` to integrate a property that is supposed to be editable via CK Editor in the Neos UI. The analogous mechanism in PresentationObject factories is the built-in protected method `getEditableProperty`.

<small>*`EXAMPLE: PresentationObject Factory`*<small>

```php
/* ... */
final class TextFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @param TraversableNodeInterface $node
     * @return TextInterface
     */
    public function forTextNode(TraversableNodeInterface $node): TextInterface
    {
        // Optional: Use assertions to ensure the incoming node type
        assert($node->getNodeType()->isOfType('Vendor.Site:Content.Text'));

        return new Text(
            $this->getEditableProperty($node, 'content', true)
        );
    }
}
```

### `getEditableProperty` Parameters

| name          | type                                                                                                                                                                                | description                                                                                                            | default value |
|---------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------|---------------|
| $node         | [`TraversableNodeInterface`](https://github.com/neos/neos-development-collection/blob/master/Neos.ContentRepository/Classes/Domain/Projection/Content/TraversableNodeInterface.php) | The node that holds the property                                                                                       |               |
| $propertyName | `string`                                                                                                                                                                            | The name of the property                                                                                               |               |
| $block        | `boolean`                                                                                                                                                                           | If true, an additional `<div>` is wrapped around the property value (sometimes needed for a proper editing experience) | `false`       |



## Find nodes with a filter string

PresentationObject factories provide the protected method `findChildNodesByNodeTypeFilterString` as a shortcut for filtering the children of a given node.

### `findChildNodesByNodeTypeFilterString` Parameters

| name                  | type                                                                                                                                                                                | description                                                               | default value |
|-----------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------|---------------|
| $parentNode           | [`TraversableNodeInterface`](https://github.com/neos/neos-development-collection/blob/master/Neos.ContentRepository/Classes/Domain/Projection/Content/TraversableNodeInterface.php) | The node whose children are to be filtered                                |               |
| $nodeTypeFilterString | `string`                                                                                                                                                                            | A node type filter string (e.g. `Neos.Neos:Document,!Neos.Neos:Shortcut`) |               |

## Composition

If you have a component that receives arbitrary content, it's fine to create a fallback, so the content can be passed via `props`:

<small>*`EXAMPLE: Resources/Private/Fusion/Presentation/Leaf/Container/Container.fusion`*<small>

```fusion
prototype(Vendor.Site:Leaf.Container) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Container\\ContainerInterface'

    renderer = afx`
        <div class="container">
            {props.content || presentationObject.content}
        </div>
    `
}
```

In Integration, components can be assembled like this:

<small>*`EXAMPLE: Resources/Private/Fusion/Integration/Content/Image.fusion`*<small>

```fusion
prototype(Vendor.Site:Content.Stage) < prototype(Neos.Neos:ContentComponent) {
    renderer = Vendor.Site:Leaf.Container {
        presentationObject = ${Vendor.Site.Container.forStageNode(node)}
        content = Vendor.Site:Composite.TextWithHeadline {
            presentationObject = ${Vendor.Site.TextWithHeadline.forStageNode(node)}
        }
    }
}
```

## Applying ContentElementWrapping to nested components

PresentationObject components are "dumb", in that they should not have access to any data outside their own interface. This leads to a problem when rendering nested content elements that need to be editable on lower levels.

The Neos UI requires metadata to treat certain DOM nodes as content elements and render things like the blue selection border, the inline toolbar and CK editor. When you render a tree of value objects, the meta information about nodes gets lost on the way, so only the outer-most element is going to be rendered with `ContentElementWrapping`.

> **Side note:** A similar problem exists in plain AtomicFusion when you try to propagate nested `Neos.Fusion:DataStructure`s through a nested component tree. The solution applied there usually is to add an additional property `__node` to your data structure and apply `ContentElementWrapping` by overriding the target component prototype in place.

`PackageFactory.AtomicFusion.PresentationObjects` comes with a workaround to address this problem. This section is going to introduce this solution by an example.

Let's assume, we have two components `Deck` and `Card`. The accompanying `DeckInterface` has a method `getCards` which returns an array of `CardInterface`s. We now want to write a `DeckFactory` method `forTeaserList`, that builds a `Deck` in which every `Card` is editable.

### The `SelfWrapping` trait

First, we need to apply the `SelfWrapping` trait to the card component like this:

<small>*`EXAMPLE: PresentationObject`*<small>

```php
/* ... */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\SelfWrapping;

final class Card implements CardInterface
{
    use SelfWrapping;

    public function __construct(
        /* ... */
        ?callable $wrapper = null
    ) {
        /* ... */
        $this->wrapper = $wrapper;
    }
}
```

The `SelfWrapping` trait introduces a method `wrap` to our `Card` object, that we will later use for `ContentElementWrapping` in Fusion. This is also why we added the `?callable $wrapper = null` parameter to our constructor and assigned it to `$this->wrapper`. The `SelfWrapping` trait will this closure if it is set.

### `ContentElementWrapping` in Fusion

Now that we have our `wrap` method, we can use it in Fusion with an `@process` annotation like this:

<small>*`EXAMPLE: Resources/Private/Fusion/Presentation/Composite/Card/Card.fusion`*<small>

```fusion
prototype(Vendor.Site:Composite.Card) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = 'Vendor\\Site\\Presentation\\Card\\CardInterface'

    renderer.@process.wrap.@position = 'end 9999'
    renderer.@process.wrap = ${presentationObject.wrap(value)}
}

```

It's recommended to put this at the very end of your processing-chain via `@position = 'end 9999'` to ensure that it wraps the entire component, including other `@process` wrappings.

### Creating a wrapper closure in the PresentationObject Factory

PresentationObject factories provide the protected method `createWrapper` that returns a closure that can be processed by the `SelfWrapping` trait. It takes a `TraversableNodeInterface` and a `PresentationObjectComponentImplementation` as parameters. The latter might seem a little strange, but it's just the PHP instance that represents our PresentationObject component and can be accessed via `this` (see the second example below the next one).

<small>*`EXAMPLE: PresentationObject Factory`*<small>

```php
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\PresentationObjectComponentImplementation;

/* ... */
final class DeckFactory extends AbstractComponentPresentationObjectFactory
{
    public function forTeaserList(TraversableNodeInterface $teaserListNode, PresentationObjectComponentImplementation $fusionObject): DeckInterface
    {
        return new Deck(
            array_map(
                function(TraversableNodeInterface $teaserNode) {
                    return new Card(
                        $this->getEditableProperty($teaserNode, 'title'),
                        $this->createWrapper($teaserNode, $fusionObject)
                    );
                },
                $teaserListNode->findChildNodes()->toArray()
            )
        );
    }
}
```

We can now invoke the `forTeaserList` in Fusion. In the example below, you can see how `PresentationObjectComponentImplementation` is obtained from `this`:

<small>*`EXAMPLE: Resources/Private/Fusion/Integration/Generated/LatestNews.fusion`*<small>

```fusion
prototype(Vendor.Site:Generated.TeaserList) < prototype(Neos.Neos:ContentComponent) {
    renderer = Vendor.Site:Composite.Deck {
        presentationObject = ${Vendor.Site.Deck.forTeaserList(node, this)}
    }
}
```

This solution is a bit elaborate and is likely to be replaced by a more concise method in the future. For the time being, it solves the problem sufficiently.

## The UriService

PresentationObject factories come with a built-in UriService that can be accessed via `$this->uriService`. This service covers all your need for creating URIs to nodes, assets and the like.

The following methods are available:

### `getNodeUri`

Generates the URI for a given document node.

#### Parameters

| name | type | description | default value |
|------|------|-------------|---------------|
| $documentNode     | [`TraversableNodeInterface`](https://github.com/neos/neos-development-collection/blob/master/Neos.ContentRepository/Classes/Domain/Projection/Content/TraversableNodeInterface.php) | The node for which a URI is to be generated | |
| $absolute | `boolean` | If true, an absolute URI will be generated | `false` |

### `getResourceUri`

Generates an URI for a static resource.

#### Parameters

| name | type | description | default value |
|------|------|-------------|---------------|
| $packageKey | string | The package key for the package containing the static resource | |
| $resourcePath | string | The path to the static resource within the package relative to `Resources/Public` | |

### `getAssetUri`

Generates the URI for a given asset.

#### Parameters

| name | type | description | default value |
|------|------|-------------|---------------|
| $asset | [`AssetInterface`](https://github.com/neos/neos-development-collection/blob/master/Neos.Media/Classes/Domain/Model/AssetInterface.php) | The asset for which a URI is to be generated | |

### `getDummyImageBaseUri`

Provides a URI to a dummy image generated by [Sitegeist.Kaleidoscope](https://github.com/sitegeist/Sitegeist.Kaleidoscope).

### `getControllerContext`

Gives you access to a [ControllerContext](https://github.com/neos/flow-development/blob/master/Neos.Flow/Classes/Mvc/Controller/ControllerContext.php), which allows you to generate arbitrary internal URIs with the [UriBuilder](https://github.com/neos/flow-development-collection/blob/master/Neos.Flow/Classes/Mvc/Routing/UriBuilder.php).

### `resolveLinkUri`

Resolves URIs with the special `asset://` and `node://` protocols.

#### Parameters

| name | type | description | default value |
|------|------|-------------|---------------|
| $rawLinkUri | string | The string containing the URI | |
| $subgraph | [ContentContext](https://github.com/neos/neos-development-collection/blob/master/Neos.Neos/Classes/Domain/Service/ContentContext.php) | A reference content context required to resolve `node://` URIs | |

---

<div align="center">
    <a href="./02_PresentationObjectFactories.md">&lt; 2. Content integration with PresentationObject Factories</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./04_Kickstarter.md">4. Kickstarter &gt;</a>
</div>