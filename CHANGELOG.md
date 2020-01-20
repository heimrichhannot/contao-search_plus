# Changelog
All notable changes to this project will be documented in this file.

## [1.1.2] -2020-01-20
- fixed backend layout for rebuild the search index
- catch an database exception when inserting to long words

## [1.1.1] -2018-02-22

#### Fixed
* only store first 2000 letters of pdf in `tl_search` 
* do not index numbers, words that are less than 2 letters long, or .pdf file names in `tl_search_index`

## [1.1.0] -2018-02-22

#### Added
* option to disable pdf indexing
* enhanced translations

## [1.0.27] - 2017-07-21

### Fixed
- added action (jumpTo) to wrong template

### Changed
- updated readme

## [1.0.26] - 2017-07-20

### Fixed
- wrong namespace for PageModel (ignore search jumpTo)
- deprecated call in ModuleSearchPlus

## [1.0.25] - 2017-07-20

### Added 
- set max pdf parsing file size in settings

### Fixed
- pdf size calculation

## [1.0.24] - 2017-07-19

### Added
- PHP 7 compatibility

## [1.0.23] - 2017-05-09

### Fixed
- composer.json

## [1.0.22] - 2017-05-09

### Added
- php 7 support

## [1.0.21] - 2017-04-12
- created new tag

## [1.0.20] - 2017-04-06

### Changed
- added php7 support. fixed contao-core dependency

## [1.0.19] - 2016-12-06

### Fixed
- SearchResult::isValidFile() now checks url against file $_GET parameter 
