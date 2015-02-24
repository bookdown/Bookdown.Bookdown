<?php
namespace Bookdown\Content;

class LayoutProcessor
{
    protected $page;

    public function __invoke(ContentPage $page)
    {
        $file = $page->getTargetFile();
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
            '{TITLE}' => $page->getTitle(),
            '{CONTENT}' => $content,
        );

        $html = strtr($tpl, $strtr);
        file_put_contents($file, $html);
    }
}
