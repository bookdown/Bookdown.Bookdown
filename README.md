# Bookdown

> **WARNING:** This project is incomplete and still under heavy development. Use at your own risk.

Bookdown is a Markdown-oriented static-page generator geared toward "DocBook-like" documentation, as opposed to blogs or other kinds of static sites. Read more about it at <http://bookdown.github.io>.

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
