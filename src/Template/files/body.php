<body>
<?= $this->render('navheader'); ?>
<?= $this->page->isIndex()
    ? $this->render('toc')
    : '' ;
?>
<?= file_get_contents($this->page->getTarget()); ?>
<?= $this->render('navfooter'); ?>
</body>
