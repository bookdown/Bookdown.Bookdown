<?php
echo $this->render('navheader');
echo $this->page->isIndex() ? $this->render('toc') : '';
echo $this->html;
echo $this->render('navfooter');
?>
