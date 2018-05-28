# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/).

Thanks to all [contributors](https://github.com/bookdown/Bookdown.Bookdown/graphs/contributors)!

## 1.1.0

- Added "bookdown/themes" as a Composer dependency. This makes it easy to theme
  your Bookdown pages.

- Improvements to how "href" and "id" attributed are handled.

- Improvements to nested TOC lists.

- Convert relative .md hrefs to .html, so that links to .md files will work in
  un-converted Markdown sources, but when converted to HTML by Bookdown the same
  links will point to the rendered HTML file.

## 1.0.0

- Now buids a JSON search index file for Lunr, et al.

- Fixed error with toc-depth headings on single pages.

- Removed tocDepth=1 as a special case, and added Page::getLevel().

- Does not stop build process if some images are missing.

- Fixed #48 by wrapping html content in special div.

- Added support to disable numbering.

- Replaced Monolog with an internal psr/log implementation (Stdlog).

- Updated commonmark and webuni dependencies.

- Dropped support for PHP 5.5.x and earlier.

- Now complies with pds/skeleton.

- Updated tests and docs.

## 1.0.0-beta1

This is the first beta release, with numerous fixes, improvements, and
additions. It is a "Google Beta" release; this means we are paying special
attention to avoiding BC breaks, but one or two may be necessary.

- Override values in bookdown.json are no longer relative to the bookdown.json
  directory.

- International and multibyte characters are now rendered correctly (cf. #12,
  #13, #34, et al.).

- Add UTF8 meta in the template header.

- Added `--root-href` as a command-line option.

- Added a "copy images" process to download/copy images to target path.

- Added CommonMark extension support.

- Added Markdown table support via webuni/commonmark-table-extension.

- Added TOC depth support for multiple books and index pages.

- Added "copyright" as a bookdown.json entry.

- Fixed header id attributes to be valid for href anchors, making them
  compatible with Javascript and CSS.

- Various updates to the README and other documentation.

Thanks, as always, to all our contributors; special thanks to Sandro Keil, who
delivered several important features in this release.
