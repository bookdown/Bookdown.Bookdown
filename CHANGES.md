This is the first beta release, with numerous fixes, improvements, and additions. It is a "Google Beta" release; this means we are paying special attention to avoiding BC breaks, but one or two may be necessary.

- Override values in bookdown.json are no longer relative to the bookdown.json directory.

- International and multibyte characters are now rendered correctly (cf. #12, #13, #34, et al.).

- Add UTF8 meta in the template header.

- Added `--root-href` as a command-line option.

- Added a "copy images" process to download/copy images to target path.

- Added CommonMark extension support.

- Added Markdown table support via webuni/commonmark-table-extension.

- Added TOC depth support for multiple books and index pages.

- Added "copyright" as a bookdown.json entry.

- Fixed header id attributes to be valid for href anchors, making them compatible with Javascript and CSS.

- Various updates to the README and other documentation.

Thanks, as always, to all our contributors; special thanks to Sandro Keil, who delivered several important features in this release.

<https://github.com/bookdown/Bookdown.Bookdown/graphs/contributors>
