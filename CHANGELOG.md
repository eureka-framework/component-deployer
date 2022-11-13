# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

```
## [tag] - YYYY-MM-DD
[tag]: https://github.com/eureka-framework/component-deployer/compare/1.3.0...master
### Changed
 - Change 1
### Added
 - Added 1
### Removed
 - Remove 1
```
----


## [2.0.0] - 2022-11-13
[2.0.0]: https://github.com/eureka-framework/component-deployer/compare/1.3.0...2.0.0
### Added
 - PHP Stan
### Removed
 - PHP Compatibility
### Changed
 - Update CI GitHub actions
 - Update makefile
 - Update Readme
 - Some phpdoc improvement or check from phpstan analyze
 - /!\ BC Break: Remove usage of container::getParameter() & use setter after new instance

----


## [1.3.0] - 2020-12-10
[1.3.0]: https://github.com/eureka-framework/component-deployer/compare/1.2.0...1.3.0
### Added
 - Step 099 to clear app cache
### Changed
 - All main script are now executed from dev source, not anymore from installed source

## [1.2.0] - 2020-11-17
[1.2.0]: https://github.com/eureka-framework/component-deployer/compare/1.1.0...1.2.0
### Added
 - Script to initialize some symlinks

## [1.1.0] - 2020-11-06
[1.1.0]: https://github.com/eureka-framework/component-deployer/compare/1.0.0...1.1.0
### Changed
 - Minor changes in deployment scripts
 - Some step now reserved for Deployer itself

## [1.0.0] - 2020-10-30
[1.0.0]: https://github.com/eureka-framework/component-deployer/compare/0.3.0...1.0.0
### Changed
 - New require PHP 7.4+
 - Fix & improve deployer
 - Merge Clean & Install for composer

----

## [0.3.0] - 2019-12-02
[0.3.0]: https://github.com/eureka-framework/component-deployer/compare/0.2.0...0.3.0
### Changed
 - Fix recursive perms directories

----

## [0.2.0] - 2019-11-23
[0.2.0]: https://github.com/eureka-framework/component-deployer/compare/0.1.0...0.2.0
### Added
 - Fix script description

----

## [0.1.0] - 2019-10-14
### Added
 - Add composer.json for dependencies
 - Add Export script
 - Add all install scripts
 - Add Link script
 - Add deployer script
 - Integrate the starting step into main install script
