<?php
namespace Bookdown\Content;

class Processor
{
    protected $converters;

    public function __construct(array $processors = null)
    {
        $this->processors = $processors;
    }

    public function __invoke(ContentList $list)
    {
        foreach ($this->processors as $processor) {
            foreach ($list->getItems() as $item) {
                $processor($item);
            }
        }
    }
}
