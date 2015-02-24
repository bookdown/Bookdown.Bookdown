# Bookdown

> **WARNING:** This project is incomplete and still under heavy development. Use at your own risk.

Bookdown is a Markdown-oriented static-page generator geared toward "DocBook-like" documentation, as opposed to blogs or other kinds of static sites.

Bookdown aims to emulate DocBook HTML presentation, replacing DocBook markup aspects with Markdown, and replacing DocBook organizational elements with JSON. Each individual Markdown file is treated as a "page" and each directory of Markdown files is treated as a "collection of pages". (This project does not deal in semantic notions of "chapters", "sections," "articles," and so on -- a pages are pages are pages.)

The pages in each collection are organized according to the order specified in a `bookdown.json` file in each directory. Metadata about the collection is also specified in that `bookdown.json` file.

## Demo

To see a demonstration of Bookdown, clone this repository, then:

1. issue `composer install` to install the dependencies, and

2. issue `php bin/bookdown.php {$origin} {$target}` to run the generator.

The `{$origin}` is a top-level `bookdown.json` file, and the `{$target}` is a directory to which the HTML files will be rendered.

Note how each rendered `index.html` file is a table-of-contents, and how each rendered page has numbered headings with their own target IDs.

## The `bookdown.json` file

Each directory should have a `bookdown.json` file with a "title" string value (to indicate the title of the pages grouped into that directory) and a "content" hash of page names and origin files.  An origin file may be yet another `bookdown.json` file, indicating a sub-collection of pages.

```json
{
    "title": "My Project Manual",
    "content": [
        "overview": "overview.md",
        "getting-started": "beginners/bookdown.json",
        "advanced-topics": "advanced/bookdown.json",
        "conclusion": "end.md",
    ]
}
```

If you have `allow_url_fopen` enabled, you can also use URLs as the origin file values, meaning that the origin files and bookdown.json files can reside on remote servers, such as Github.

Note that the `bookdown.json` file need not be named `bookdown.json` per se; any `.json` file will be assumed to be a `bookdown.json` file, and any other file will be assumed to be a page file.

## Todo

(In no particular order.)

- Extract file-reading and file-writing to an injectable Fileio class.

- Generate output via an injected output callable, so that any command/console system can display output (or so a logging system can log output).

- Use templates for the table of contents listings instead of embedding it, perhaps as a helper

- Allow bookdown.json to specify `tocdepth`, indicating how many levels to show on the table of contents

- Allow bookdown.json to specify `numbering`, indicating how to number the pages at this level (decimal, upper-alpha, lower-alpha, upper-roman, lower-roman)

- Allow bookdown.json to specify authors, editors, and other meta-data

- Render authors, editors, and other meta-data on TOC index pages

- Add a sidebar-style navigational element

- Add a breadcrumb-style naviagtional element

- Take a PHPDocumentor structure.xml file and convert to Markdown files for conversion through Bookdown

- A link-rewrite processor
