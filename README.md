# Search Plus

Extend the contao core search, to achieve more features like pdf search.

## Features

### Dependencies

Don't worry, install via composer and all dependencies will be resolved like magic.

- [smalot/pdfparser] (https://github.com/smalot/pdfparser)

### PDF Search

- index pdf files that are referenced inside searched pages and must be locally (contao filesystem) stored
- parse pdf files with smalot/pdfparser
- make usage of meta information from tl_files to provide better file titles (consider language too)
- group the results in the result list
- select the search order (show pages first, show files first or by relenvance)
