# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- feat: add logo.png for project browser
- ci: allow manually triggering the CI workflow
- feat: declare Drupal 12 compatibility in `core_version_requirement`
- build(deps): allow drupal/coder ^9

### Changed
- ci: run Upgrade Status on Drupal 11.3 to report Drupal 12 readiness
- docs(readme): update the supported Drupal versions
- refactor: use constructor property promotion
- ci: test Drupal 11.4 as a stable version
- build(docker): use Drupal 11.4 as default base image
- ci: run Upgrade Status on Drupal 11.4
- docs(gitlab-ci): refresh outdated Drupal versions in comments

### Removed
- ci: remove PhpDeprecationDetector (phpdd) job, tool and docs

### Fixed
- fix(ci): use the PHPMD 3 `analyze` command
- build: allow the symfony/runtime composer plugin required by Drupal 11.4
- ci: rename the ci.yml workflow to test.yml so GitHub registers it again
- fix: remove deprecated ConfigEntityInterface::trustData() call
- fix: replace the obsolete docker-compose command by docker compose
- fix(phpcs): remove the stray `>` after the exclude patterns
- fix(cspell): add codesniffer to the project dictionary

## [1.2.5] - 2026-02-13
### Added
- add official support of drupal 10.6
- add official support of drupal 11.3

## [1.2.4] - 2025-11-07
### Added
- add official support of drupal 10.5

### Removed
- drop coverage of Drupal 10.0.x
- drop coverage of Drupal 10.1.x
- drop coverage of Drupal 10.2.x
- drop coverage of Drupal 10.3.x
- drop coverage of Drupal 10.4.x
- remove legacy 'version_compare' from Tests suites

### Fixed
- fix Phpcs documentation comment contains forbidden comment

## [1.2.3] - 2025-05-15
### Added
- add official stable support of drupal 10.4
- add official stable support of drupal 11.1

### Fixed
- fix wrong @covers in order to calm-down phpstan

### Removed
- drop support of Drupal 9.x
- remove scanning CSS/JS with phpcs (deprecated and support will be removed in PHP_CodeSniffer 4.0)

## [1.2.2] - 2024-08-20
### Fixed
- fix obsolete docker-compose command in CIs

### Added
- add official support of drupal 10.3
- add official support of drupal 11.0

### Removed
- remove legacy version annotation on docker-compose.yml

### Changed
- mark all phpunit provider as Static

## [1.2.1] - 2024-05-31
### Removed
- drop tests support on Drupal <= 9.4

### Added
- add support of Paragraph
- add Drupal Gitlab-CI
- add support of Drupal 10.1, 10.2, 10.3
- add cpsell project words for Gitlab-CI

## [1.2.0] - 2022-12-02
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

[Unreleased]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.2.5...HEAD
[1.2.5]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.2.3...1.2.4
[1.2.3]: hittps://github.com/antistatique/drupal-factory-lollipop/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/antistatique/drupal-factory-lollipop/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/antistatique/drupal-factory-lollipop/releases/tags/1.0.0
