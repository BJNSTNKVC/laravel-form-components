# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- A changelog recording notable changes for each release.

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
