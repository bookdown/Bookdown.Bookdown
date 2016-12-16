<?php
echo $this->render('navheader');
echo $this->page->isIndex() ? $this->render('toc') : '';
echo sprintf(
    '<div id="htmlcontainer">%s</div>',
    $this->html
);
echo $this->render('navfooter');
?>
