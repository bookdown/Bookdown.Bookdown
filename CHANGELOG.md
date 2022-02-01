# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/).

Thanks to all [contributors](https://github.com/bookdown/Bookdown.Bookdown/graphs/contributors)!

## 2.0.0

This release raises the minimum PHP version to ^7.4||^8.0, and the
league/commonmark version to ^2.0.

All other changes are in relation to those requirements; no other API or
functional changes have been made.

To upgrade from 1.x to 2.x, change your Bookdown project `composer.json` to
require `{"bookdown/bookdown": "^2.0"}` and issue `composer update`.

You should not have to change any of your confgurations, except in one case:
because the CommonMark library has been upgrade to 2.0, the `{"extenions":
{"commonmark": [ ... ] } }` entries in `_bookdown.json` will have to be changed
to the upgraded versions and class names.
