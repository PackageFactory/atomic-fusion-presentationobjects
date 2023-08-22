<div align="center">
    <a href="./02_PresentationObjectFactories.md">&lt; 3. Slots</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./05_Kickstarter.md">5. Kickstarter &gt;</a>
</div>

---

# 4. Integration Recipes

This article discusses various helper APIs you can use for integrating content via PresentationObject factories.

## Find nodes

PresentationObject factories provide the `ContentRepositoryRegistry` as a member,
which provide the current subgraph via the `subgraphForNode` method.
The subgraph can then be queried using the usual graph operations.

## The UriService

PresentationObject factories come with a built-in UriService that can be accessed via `$this->uriService`. This service
covers all your need for creating URIs to nodes, assets and the like.

The following methods are available:

### `getNodeUri`

Generates the URI for a given document node.

#### Parameters

| name          | type                                                                                                                                                            | description                                 | default value |
|---------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------|---------------|
| $documentNode | [`Node`](https://github.com/neos/neos-development-collection/blob/master/Neos.ContentRepository/Classes/Domain/Projection/Content/TraversableNodeInterface.php) | The node for which a URI is to be generated |               |
| $absolute     | `boolean`                                                                                                                                                       | If true, an absolute URI will be generated  | `false`       |

### `getResourceUri`

Generates an URI for a static resource.

#### Parameters

| name          | type   | description                                                                       | default value |
|---------------|--------|-----------------------------------------------------------------------------------|---------------|
| $packageKey   | string | The package key for the package containing the static resource                    |               |
| $resourcePath | string | The path to the static resource within the package relative to `Resources/Public` |               |

### `getAssetUri`

Generates the URI for a given asset.

#### Parameters

| name   | type                                                                                                                                   | description                                  | default value |
|--------|----------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------|---------------|
| $asset | [`AssetInterface`](https://github.com/neos/neos-development-collection/blob/master/Neos.Media/Classes/Domain/Model/AssetInterface.php) | The asset for which a URI is to be generated |               |

### `getDummyImageBaseUri`

Provides a URI to a dummy image generated
by [Sitegeist.Kaleidoscope](https://github.com/sitegeist/Sitegeist.Kaleidoscope).

### `getControllerContext` or `->controllerContext`

Gives you access to
a [ControllerContext](https://github.com/neos/flow-development/blob/master/Neos.Flow/Classes/Mvc/Controller/ControllerContext.php),
which allows you to generate arbitrary internal URIs with
the [UriBuilder](https://github.com/neos/flow-development-collection/blob/master/Neos.Flow/Classes/Mvc/Routing/UriBuilder.php).

### `resolveLinkUri`

Resolves URIs with the special `asset://` and `node://` protocols.

#### Parameters

| name        | type                                                                                                                                                                              | description                                                    | default value |
|-------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------|---------------|
| $rawLinkUri | string                                                                                                                                                                            | The string containing the URI                                  |               |
| $subgraph   | [ContentSubgraphInterface](https://github.com/neos/neos-development-collection/blob/9.0/Neos.ContentRepository.Core/Classes/Projection/ContentGraph/ContentSubgraphInterface.php) | A reference content context required to resolve `node://` URIs |               |

## The EnumProvider

All backed enums can be provided to the inspector or the Fusion runtime using the EnumProvider.
This makes the enum itself the single source of discrete values a node or presentation object property may have,
obsoleting value adjustments in Fusion, configuration etc.

As an example, we use the following enum:

```php
<?php
 
declare(strict_types=1);

namespace Acme\Site\Presentation\Block\Headline;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum HeadlineType:string
{
    use StringComponentVariant

    const TYPE_H1 = 'h1';
    const TYPE_H2 = 'h2';
    const TYPE_H3 = 'h3';
}
```

### As a node type postprocessor

We can use the EnumProvider's node type postprocessor capabilities to make the enum's cases available in the Inspector.

When we now declare our NodeType property, we do this as follows:

```yaml
  properties:
      headline:
          type: string
          ui:
              inspector:
                  editor: Neos.Neos/Inspector/Editors/SelectBoxEditor
                  editorOptions:
                      values: [ ]
  postprocessors:
      headline-types:
          postprocessor: EnumProvider
          postprocessorOptions:
              enumName: Acme\Site\Presentation\Block\Headline\HeadlineType
              propertyNames:
                  - headline
```

The initial values are left empty and will be completely populated by the postprocessor. While the postprocessor is the
same for all enums, there are two configuration options available:

* enumName: The enum's fully qualified PHP class name
* propertyNames: A list of property names to apply this enum's values to

### As a data source

If for some reason the postprocessor does not suffice, there is also the possibility to use the provider as a data
source.
Be aware that though more flexible, data sources are called on each load of the inspector, impacting performance.
The provider can be used as a data source as follows:

```yaml
  properties:
      headline:
          type: string
          ui:
              inspector:
                  editor: Neos.Neos/Inspector/Editors/SelectBoxEditor
                  editorOptions:
                      dataSourceIdentifier: packagefactory-atomicfusion-presentationobjects-enumcases
                      dataSourceAdditionalData:
                          enumName: Acme\Site\Presentation\Block\Headline\HeadlineType
```

### As an EEL helper

If you need the enum's values in Fusion, you can declare the provider as an EEL helper

```yaml
Neos:
    Fusion:
        defaultContext:
            Enum: EnumProvider
```

and use it in Fusion to get the cases or values.

```neosfusion
cases = ${Enum.getCases('Acme\Site\Presentation\Block\Headline\HeadlineType')}
values = ${Enum.getValues('Acme\Site\Presentation\Block\Headline\HeadlineType')}
```

---

<div align="center">
    <a href="./02_PresentationObjectFactories.md">&lt; 3. Slots</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./05_Kickstarter.md">5. Kickstarter &gt;</a>
</div>
