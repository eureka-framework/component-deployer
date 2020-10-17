# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [1.0.0] - 2020 (unreleased)
### Changed
 * New require PHP 7.4+
 * All collections now use an abstract class (menu, breadcrumb, carousel & notifications)
 * Minor fixed & improvements
### Added
 * Added tests
### Removed
 * Flash notification class (now handled directly in session trait + session)
 * Compilation for phar archive: this component must be included with composer


## [0.3.0] - 2019-12-02
### Changed
 * Fix recursive perms directories

## [0.2.0] - 2019-11-23
### Added
 * Fix script description
 


## [0.1.0] - 2019-10-14
### Added
 * Add composer.json for dependencies
 * Add Export script
 * Add all install scripts
 * Add Link script
 * Add deployer script
 * Integrate the starting step into main install script