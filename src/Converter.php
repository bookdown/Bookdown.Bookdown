<?php
namespace Bookdown\Content;

use League\CommonMark\CommonMarkConverter;

class Converter
{
    protected $converter;

    public function __construct(CommonMarkConverter $converter)
    {
        $this->converter = $converter;
    }

    public function __invoke(ContentList $list)
    {
        $items = $list->getItems();
        foreach ($items as $item) {

        }
    }
}
