<?php
namespace Bookdown\Content;

class LayoutProcessor
{
    protected $item;

    public function __invoke(ContentItem $item)
    {
        $file = $item->getTargetFile();
        $content = file_get_contents($file);
        $tpl = <<<TPL
<html>
<head>
    <title>{TITLE}</title>
</head>
<body>
{CONTENT}
</body>
</html>
TPL;

        $strtr = array(
            '{TITLE}' => $item->getTitle(),
            '{CONTENT}' => $content,
        );

        $html = strtr($tpl, $strtr);
        file_put_contents($file, $html);
    }
}
