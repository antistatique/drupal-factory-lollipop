# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Removed
- remove satackey/action-docker-layer-caching on Github Actions
- drop support of drupal below 9.3.x

### Added
- add official support of drupal 9.5 & 10.0

### Fixed
- fix deprecation of theme start for tests
- fix call to deprecated constant FILE_STATUS_PERMANENT
- fix call to deprecated method assertEqual()
- fix call to deprecated method setMethods()
- fix call to deprecated method drupalPostForm()
- fix calling responseContains with more than one argument is deprecated
- fix adding non-existent permissions to a role is deprecated

### Changed
- re-enable PHPUnit Symfony Deprecation notice

## [1.1.0] - 2022-08-12
### Added
- add support Drupal 9.4 & 9.5

### Changed
- drop support of Drupal 8
- drop support of Drupal 9.0 (keep 9.1+)
- fix D9.3 test failures - "Role label is expected to be a string."
- use the drupal 9.1 service password_generator instead of deprecated user_password()

## [1.0.0] - 2022-08-12
### Added
- init module

[Unreleased]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.1.0...HEAD
[1.1.0]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/antistatique/drupal-factory-lollipop/releases/tags/1.0.0
