# Search Plus

Extend the contao core search, to achieve more features like pdf search.

## Features

- Support Access-Control-Allow-Origin within be_rebuild_index.html5

### Dependencies

Don't worry, install via composer and all dependencies will be resolved like magic.

- [smalot/pdfparser] (https://github.com/smalot/pdfparser)

### PDF Search

- index pdf files that are referenced inside searched pages and must be locally (contao filesystem) stored
- parse pdf files with smalot/pdfparser
- make usage of meta information from tl_files to provide better file titles (consider language too)
- group the results in the result list
- select the search order (show pages first, show files first or by relenvance)

## Usage

### Install 

Add following line to your composer.json required section:

    "heimrichhannot/contao-search_plus" : "~1.0.27"
    
You can also add the package via commandline:

    composer require heimrichhannot/contao-search_plus:~1.0.27

### Templates

You need to add two templates: `mod_search_simple` and `mod_search_advanced`. In your `mod_search` template you need to add `<?php echo $this->form ?>` to output the correct search form template. 