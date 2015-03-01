# Bookdown

> **WARNING:** This project is incomplete and still under heavy development. Use at your own risk.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bookdown/Bookdown.Bookdown/build-status/master)

Bookdown generates [DocBook](http://docbook.org)-like HTML output using [Markdown](http://daringfireball.net/projects/markdown/) and JSON files instead of XML.

Bookdown is especially well-suited for publishing project documentation to GitHub Pages.

Read more about it at <http://bookdown.github.io>.

## Todo

(In no particular order.)

- Allow bookdown.json to specify `tocdepth`, indicating how many levels to show on the table of contents

- Allow bookdown.json to specify `numbering`, indicating how to number the pages at this level (decimal, upper-alpha, lower-alpha, upper-roman, lower-roman)

- Allow bookdown.json to specify authors, editors, copyright, date-generated, and other meta-data, then render the meta-data on TOC index pages

- Add a sidebar-style navigational element

- Add a breadcrumb-style naviagtional element

- Take a PHPDocumentor structure.xml file and convert to Markdown files for conversion through Bookdown

- A link-rewrite processor

- No more default "target", must always be explicit

- Copying of images and other resources from the origin to the target

- Pre-process and post-process behavior to copy and/or remove site files

- Add "beforeToc" and "afterToc" elements to specify files to place before and after the table of contents

- Treat the root page as different from other indexes, allow it to be a nice "front page" for sites
