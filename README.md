# Bookdown

> **WARNING:** This project is incomplete and still under heavy development. Use at your own risk.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/build-status/master)

Bookdown generates [DocBook](http://docbook.org)-like HTML output using [Markdown](http://daringfireball.net/projects/markdown/) and JSON files instead of XML.

Bookdown is especially well-suited for publishing project documentation to GitHub Pages.

Read more about it at <http://bookdown.io>.

## Templates
This is a list of custom bookdown.io templates

* [tobiju/bookdown-bootswatch-templates](https://github.com/tobiju/bookdown-bootswatch-templates "Bootswatch styles and syntax highlighting")
* [bdudelsack/bookdown-template](https://github.com/bdudelsack/bookdown-template "Template for the bookdown project using Bootstrap and HighlightJS")

## Tests

Bookdown has 100% test coverage. To run the tests, install [PHPUnit](http://phpunit.de), then issue `phpunit` at the repository root.

## Todo

(In no particular order.)

- new `bookdown.json` elements

    - `"numbering"`: indicates how to number the pages at this level (decimal, upper-alpha, lower-alpha, upper-roman, lower-roman)

    - `"authors"`: name, note, email, and website of book authors

    - `"editors"`: name, note, email, and website of book editors

    - `"beforeToc"`: indicates a Markdown file to place on the index page before the TOC

    - `"afterToc"`: indicates a Markdown file to place on the index page after the TOC

    - `"subtitle"`: indicates a subtitle on an index page

- navigational elements

    - sidebar of siblings at the current level

    - breadcrumb-trail of parents leading to the current page

- features

    - Automatically add a "date/time generated" value to the root config object and display on the root page

    - Display authors, editors, etc. on root page

    - A command to take a PHPDocumentor structure.xml file and convert it to a Bookdown origin structure (Markdown files + bookdown.json files)

    - A process to rewrite links on generated pages (this is for books collected from multiple different sources, and for changing origin `*.md` links to target `*.html` links)

    - Pre-process and post-process behavior to copy and/or remove site files

    - Treat the root page as different from other indexes, allow it to be a nice "front page" for sites
