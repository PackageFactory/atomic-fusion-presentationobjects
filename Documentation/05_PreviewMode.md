<div align="center">
    <a href="./04_Kickstarter.md">&lt; 4. Kickstarter</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
</div>

---

# Preview Mode

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

> The above example shows how `isInPreviewMode` can be set to true for all PresentationObjectComponents that are rendered in Sitegeist.Monocle. If you only want compatibility with Monocle, just copy the code above and paste it to your `Root.fusion`

---

<div align="center">
    <a href="./04_Kickstarter.md">&lt; 4. Kickstarter</a>
    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
    <a href="./00_Index.md">Index</a>
</div>