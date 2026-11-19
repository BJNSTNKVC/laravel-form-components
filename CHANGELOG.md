# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- A changelog recording notable changes for each release.
- CSS variables for the brand colour, field colours, error colour, font and corner radii, so components can match an app without overriding its stylesheet.
- Dark mode, applied inside a `dark` class or a dark `color-scheme`.
- Component behaviour runs on Alpine.js, which the app provides. Each field ships its own Alpine data, e.g. `form_password`, as a self-contained script in its own folder that the app registers with `Alpine.data()`, so revealing a password, clearing a search, growing a textarea, naming a chosen file and hiding errors on input keep working for components added after the page loads, e.g. by Livewire. Templates only name the data and its refs, the script wires up the behaviour.
- `interactive` attribute on every field and `auto-expand` attribute on the Textarea component, overriding the `interactive` and `auto_expand` config values for a single component.
- Pages that render a component link its stylesheet on their own, at the start of their `<head>`, so nothing needs publishing or adding to a layout. The package serves the stylesheet from its own route, versioned by its contents, and the link carries the CSP nonce set with `Vite::useCspNonce()`. Turn it off with the `inject_styles` config value.
- `resources/js/index.js`, which exports every field's Alpine data under its file name, e.g. `password`, so an app can import them all as one namespace.
- `form-components:install` command, which registers each field's Alpine data in the script that starts Alpine, waits for Alpine when Livewire brings it, and adds Alpine to apps without it.

### Changed

- Each component is its own module folder under `src/Components`, laid out like a Laravel app with `View/Components`, `resources/views` and `Tests`.
- Component classes moved from `Bjnstnkvc\FormComponents\View\Components\Form` to `Bjnstnkvc\FormComponents\Components\<Name>\View\Components`, e.g. `Bjnstnkvc\FormComponents\Components\Input\View\Components\Input`.
- Component views are named `form-components::<name>` instead of `form-components::form.<name>`.
- Component styles are rebuilt on Tailwind CSS 4 as `fc:` prefixed utility classes in each component's template, compiled into the shipped stylesheet. It has no preflight or unprefixed classes and keeps its utilities out of cascade layers, so it never resets or clashes with an app's own styles, Tailwind included.
- Focused fields no longer glow around their border.
- Fields and buttons use the font of the page instead of the browser's form font.
- Checked switches, checkboxes and radios take the brand colour, black by default, instead of green and the browser's blue.
- The Textarea component's `autoexpand` attribute is renamed to `auto-expand`.
- Clearing a Search with its clear button fires an `input` event, as deleting its text does.
- `@componentStyles` links to the stylesheet the package serves instead of a published copy, adds the CSP nonce, and keeps a page from linking the stylesheet twice.

### Fixed

- File fields show their title as a placeholder, and floating File titles rise once a file is chosen.
- The border no longer runs through the floating title of a fully bordered field, it opens a notch around the title instead.
- `components:publish` and `components:restore` write to `app/View/Components/Form` and `resources/views/components/form` on every platform instead of creating backslash-named files, and switch the component imports in the published config.
- With `invalidated-title` on, emptying a Password, Textarea, Select, Date, Time, Date & Time, Checkbox or Radio field marks its title invalid again, as it already did for the other fields.
- Clearing a Search with its clear button brings its error back, as deleting its text does.

### Removed

- The `label-type`, `border-radius`, `position`, `with-icon`, `with-visibility` and `with-clearing` attributes and the `form-group__*`, `form-btn`, `btn-*` and `tg-*` classes that only served the old stylesheet.
- The global script that wired up behaviour once on page load, the `form-group__*` and `fc-is-invalid` classes it relied on, and the `interactive`, `invalidated-title`, `autoexpand` and `border` attributes it read from fields.
- The `@componentScripts` directive, the `form-scripts` publish tag and the `form-components.js` script, as apps register each field's Alpine data themselves.
- The `form-styles` and `form-resources` publish tags and the `publish_path` config value, as the package serves its own stylesheet.
- The `renderVisibilityIcon()` method of the Password component and the `renderSearchIcon()` method of the Search component, as their icons no longer need a class injected.

## [1.5.1] - 2023-02-25

### Changed

- Select component: the `js-set` and `js-key` attributes are renamed to `js-data-key` and `js-data-value`.

## [1.5.0] - 2022-11-15

### Added

- Form component.
- `checked` attribute for the Checkbox and Radio components.
- `switch` attribute for the Checkbox and Radio components, which renders them as switches.

## [1.4.6] - 2022-11-13

### Fixed

- Incorrect padding on components.

## [1.4.5] - 2022-11-13

### Fixed

- Missing CSS files in the release.

## [1.4.4] - 2022-11-13

### Added

- `js-set` and `js-key` attributes for the Select component, which add data attributes to its options.

### Changed

- The Button component's `name` attribute is no longer required.
- Minor CSS adjustments.

## [1.4.3] - 2022-09-14

### Changed

- Component configuration moved from `.env` to the `form_components.php` config file.

## [1.4.2] - 2022-09-12

### Changed

- The `Views` namespace is renamed to `View` to match Laravel's naming convention.

## [1.4.1] - 2022-09-11

### Fixed

- The Email component displays the correct icon.

## [1.4.0] - 2022-09-10

### Added

- Email and Search components.
- Icons can be passed to components as an attribute.

## [1.3.1] - 2022-09-05

### Fixed

- Padding of the File component icon.

## [1.3.0] - 2022-09-04

### Added

- File component.

## [1.2.1] - 2022-08-29

### Removed

- Unused imports from the service provider.

## [1.2.0] - 2022-08-29

### Added

- `border` and `border-radius` attributes.

### Changed

- Cleaned up the components' HTML and CSS structure.

## [1.1.2] - 2022-08-21

### Changed

- README adjustments.

## [1.1.1] - 2022-08-21

### Changed

- README adjustments.

## [1.1.0] - 2022-08-21

### Added

- Option to give a component's title an invalidated look.

## [1.0.0] - 2022-08-15

### Added

- Button, Checkbox, Date, DateTime, Error, Input, Password, Radio, Select, Textarea and Time components.

[Unreleased]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.5.1...HEAD
[1.5.1]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.5.0...1.5.1
[1.5.0]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.6...1.5.0
[1.4.6]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.5...1.4.6
[1.4.5]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.4...1.4.5
[1.4.4]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.3...1.4.4
[1.4.3]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.3.1...1.4.0
[1.3.1]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.1.2...1.2.0
[1.1.2]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/BJNSTNKVC/laravel-form-components/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/BJNSTNKVC/laravel-form-components/releases/tag/1.0.0
