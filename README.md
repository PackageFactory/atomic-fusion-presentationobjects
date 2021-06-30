![CI](https://github.com/PackageFactory/atomic-fusion-presentationobjects/workflows/CI/badge.svg?branch=release-1.0)

# PackageFactory.AtomicFusion.PresentationObjects

> Allows for usage of type-safe, testable presentation objects (e.g. value objects) in Atomic Fusion as a replacement for props and propsets.

## Installation

```
composer require packagefactory/atomicfusion-presentationobjects
```

## Documentation

**Attention:** You are reading the README for version 3.0! You'll find the README for 2.0 [here](https://github.com/PackageFactory/atomic-fusion-presentationobjects/blob/2.0/README.md).

1. [PresentationObjects and Components](./Documentation/01_PresentationObjectsAndComponents.md)
2. [Content integration with PresentationObject Factories](./Documentation/02_PresentationObjectFactories.md)
3. [Slots](./Documentation/03_Slots.md)
4. [Integration Recipes](./Documentation/04_IntegrationRecipes.md)
5. [Scaffolding with the Component Kickstarter](./Documentation/05_Kickstarter.md)
6. [Preview Mode](./Documentation/06_PreviewMode.md)

## Why

`PackageFactory.AtomicFusion` has been the first step in the direction of Component architecture in Neos CMS. It provides a `Component` fusion prototype that allows for writing frontend components with a clear interface for the backend.

However, that interface isn't strict. Developers are able to express requirements for their components, but those requirements aren't enforced on any level. Because of that, [PackageFactory.AtomicFusion.PropTypes](https://github.com/PackageFactory/atomic-fusion-proptypes) was created to bring the concept of [React.js PropTypes](https://reactjs.org/docs/typechecking-with-proptypes.html) to AtomicFusion.

PropTypes check incoming data against a defined schema whenever a component is invoked, thus ensuring that a component can never be rendered with invalid data. The weakness of this pattern is that any guarantee over the integrity of the component interface can only ever be made at runtime. This way integration remains error-prone.

With the advent of [Typescript](https://www.typescriptlang.org/), PropTypes have become almost obsolete in the React world, since static typings don't have an impact on bundle size and catch type-related bugs before runtime. Typescript is a superset of ECMAScript and extends the language with type-annotations for static analysis. As of right now, a similar concept for Fusion does not exist.

This is where PresentationObjects come into play.

The idea of PresentationObjects is to leverage PHPs typesystem to enforce the component interface by replacing dynamic `array`-driven props for `Neos.Fusion:Component` with actual PHP interfaces. Unlike Typescript, PresentationObjects are not a langauge extension, but just plain PHP value objects. Therefore, they allow for static analysis and also enforce the interface at runtime.

## How does it work?

This package provides a special component prototype for Fusion that allows to associate a component with a PHP interface via the `@presentationObjectInterface` annotation. `PackageFactory.AtomicFusion.PresentationObjects` then makes sure that any object that is passed to that component implements the declared interface.

PresentationObjects are Value Objects (see: https://martinfowler.com/bliki/ValueObject.html). They are immutable and are only allowed to consist of scalar properties, other value objects or arrays of the former two. They act as predictable data containers.

PresentationObjects are created by factories (see: https://en.wikipedia.org/wiki/Factory_(object-oriented_programming)). These classes have the responsibility to encapsulate specific use cases of a component, retrieve all the data needed for producing it and do the required data mapping. In order to access them in Fusion, PresentationObject factories are registered as Eel Helpers.

## Benefits

### Type-safety & Static Analysis

The most important function of PresentationObjects is to enforce the interface between domain and presentation layer using PHPs type system. Without further measures however, PHPs type system is only relevant at runtime.

Luckily, there's tools like [phpstan](https://phpstan.org/) or [psalm](https://psalm.dev/), which allow static analysis of your PHP code base.

Typesafety and static analysis comes with a lot of benefits:

1. **Catch type-related bugs before runtime.** Consequent use of [Typehints](https://docs.phpdoc.org/latest/guide/guides/types.html) ensures the correctness of your code during static analysis. Using [phpDoc types](https://docs.phpdoc.org/latest/guide/guides/types.html) allows you to even go beyond the capabilities of PHP and use patterns like Generics or Union types without them being actually supported by PHPs type system.
2. **Self-documenting interfaces.** Typehints and type annotations amend parameters and properties with the important information of what kind of data they require without the need to look it up in a separate documentation.
3. **IDE support.** Modern PHP IDEs understand Typehints and phpDoc types and can use them to provide code completion, intelligent parameter suggestions and advanced refactoring capabilities.

### Testing & QA Tooling

Since they're just PHP code, it is quite easy to write functional and unit tests for PresentationObjects and PresentationObject factories with tools like phpunit (https://phpunit.de/).

For the same reason, PresentationObjects integrate well with any QA tooling for PHP, like:

* PHP_CodeSniffer: https://github.com/squizlabs/PHP_CodeSniffer
* PHP Coding Standards Fixer: https://cs.symfony.com/
* PHPCPD: https://github.com/sebastianbergmann/phpcpd

### Separation of Concerns

The extensibility of Fusion is generally a great feature, but it also leads to ambiguity when it comes to complex data processing tasks.

You are left with several options to encapsulate effectful tasks (e.g. custom Eel-Helpers, custom FusionObjects, custom FlowQueryOperations, `Neos.Neos:Plugin`, etc.) with no real guidance as to which mechanism fits which use-case.

When using `PackageFactory.AtomicFusion.PresentationObjects` data and content integration are unambiguously handled by PresentationObject factories. Since these are PHP-Classes capable of any data operation within a Neos instance, your choice of mechanism boils down to one option.

### Debugging

Fusion is sometimes hard to debug, especially if it is unclear, where exactly a malfunction occurs. `Neos.Fusion:Debug` cannot be arbitrarily positioned in your Fusion code and needs to be rendered just like everything else. it therefore also requires the rendering process to succeed at all.

Presentation object factories allow use of the good ol' `\Neos\Flow\var_dump(); die;`-Pattern for simple debugging.

For more advanced needs the PHP-native character of PresentationObjects comes with a natural compatibility with Xdebug step debugging (https://xdebug.org/docs/remote) and profiling (https://xdebug.org/docs/profiler).

## Drawbacks

### A step away from [Neos.Fusion](https://docs.neos.io/cms/manual/rendering/fusion)

Fusion is a domain specific language that specializes on declarative rendering instructions. As a DSL, Fusion is able to enforce a certain mindset linguistically.

PresentationObjects move the concern of content integration largely over to PHP. And while PHP is a multi-paradigm language that *can* be used similarly to Fusion, it doesn't enforce that use at all.

So when using `PackageFactory.AtomicFusion.PresentationObjects`, you need to pay attention on your language use and avoid common anti-patterns. It is strongly recommended that you adhere closely to the value object pattern when writing PresentationObjects. For factories, familiarity with general PHP best practices is helpful (see for instance: https://phptherightway.com/).

> **Hint:** PHP 8 will be released soon and comes with a lot of great language features that are going to allow to write most of the patterns presented here in a much more concise fashion. Especially noteworthy are [Constructor property promotion](https://wiki.php.net/rfc/constructor_promotion) and [Named arguments](https://wiki.php.net/rfc/named_params). For more on that, have a look at this article: https://stitcher.io/blog/new-in-php-8

### Verbosity

PresentationObjects require you to write more code than plain AtomicFusion. To remedy that, this package comes with a [scaffolding tool](./Documentation/05_Kickstarter.md) to ease the creation of initial code structures.

Currently, there's also a lot of concepts involved that spread information over the Codebase (`Classes/Presentation/`, `Resources/Private/Fusion/`, `Configuration/`), thus breaking the principle of co-location.

In theory, co-location could be achieved by leveraging the `autoload.psr-4` configuration in the composer manifest (see: https://getcomposer.org/doc/04-schema.md#psr-4). However, the viability of this idea has not been proven yet.
See also the [Kickstarter section](./Documentation/05_Kickstarter.md) on how to achieve co-location.

### Fusion Interoperation

As of right now, Fusion is still the entry point for content integration. It's important to be aware of that, especially when changing factory method signatures, because this is a giant surface on which type-safety is lost.

Fusion is required to handle two major concerns:

1. **The internal content mapping and augmentation logic of Neos CMS.** Neos uses Fusion to map content repository nodes to their respective rendering instructions. It also uses Fusion to augment rendered content elements with information required by the Neos UI for inline editing.
2. **Content cache and partial page rendering.** Fusion provides the `@cache` annotation to enable individual caching instructions for different rendering paths. Its `ContentCache` service is able to resolve nested cached and uncached page fragments, thus allowing for maximum flexibility.

Future developments of this package are going to focus on solutions for those two problems.

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

see [LICENSE](./LICENSE)
