<?php
namespace Bookdown\Content;

use League\CommonMark\CommonMarkConverter;

class Processor
{
    protected $converter;

    public function __construct(CommonMarkConverter $converter)
    {
        $this->converter = $converter;
    }

    public function __invoke(ContentList $list, $target)
    {
        $items = $list->getItems();
        foreach ($items as $item) {
            $text = $item->getOriginData();
            $html = $this->converter->convertToHtml($text);
            $file = $target . $item->getTargetFile();
            $dir = dirname($file);
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($file, $html);
        }
    }
}
