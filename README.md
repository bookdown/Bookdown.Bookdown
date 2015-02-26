# Bookdown

> **WARNING:** This project is incomplete and still under heavy development. Use at your own risk.

Bookdown is a Markdown-oriented static-page generator geared toward "DocBook-like" documentation, as opposed to blogs or other kinds of static sites.

Bookdown aims to emulate DocBook HTML presentation, replacing DocBook markup aspects with Markdown, and replacing DocBook organizational elements with JSON. Each individual Markdown file is treated as a "page" and each directory of Markdown files is treated as a "collection of pages". (This project does not deal in semantic notions of "chapters", "sections," "articles," and so on -- a pages are pages are pages.)

The pages in each collection are organized according to the order specified in a `bookdown.json` file in each directory. Metadata about the collection is also specified in that `bookdown.json` file.

## Demo

To see a demonstration of Bookdown, clone this repository, then:

1. issue `composer install` to install the dependencies, and

2. issue `php bin/bookdown.php demo/_bookdown.json` to run the generator.

This will create a `_site` directory. You can then issue `php -S localhost:8080 -t _site` and browse the generated pages.

## The `bookdown.json` file

Each `bookdown.json` file has a "title" string value (to indicate the title of the page) and a "content" hash of page names and origin files.  An origin file may be a Markdown file (indicating a single page) or yet another `bookdown.json` file (indicating a sub-collection of pages).

```json
{
    "title": "My Project Manual",
    "content": {
        "overview": "overview.md",
        "getting-started": "beginners/bookdown.json",
        "advanced-topics": "advanced/bookdown.json",
        "conclusion": "end.md",
    }
}
```

If `allow_url_fopen` is enabled, you can also use URLs as the origin file values. That means that page files and `bookdown.json` files can reside on remote servers, such as Github.

Note that the `bookdown.json` file need not be named `bookdown.json` per se. Any `.json` file will be assumed to be a `bookdown.json` file, and all other files will be assumed to be page files.

## Todo

(In no particular order.)

- Allow bookdown.json to specify `tocdepth`, indicating how many levels to show on the table of contents

- Allow bookdown.json to specify `numbering`, indicating how to number the pages at this level (decimal, upper-alpha, lower-alpha, upper-roman, lower-roman)

- Allow bookdown.json to specify authors, editors, and other meta-data, then render the meta-data on TOC index pages

- Add a sidebar-style navigational element

- Add a breadcrumb-style naviagtional element

- Take a PHPDocumentor structure.xml file and convert to Markdown files for conversion through Bookdown

- A link-rewrite processor
