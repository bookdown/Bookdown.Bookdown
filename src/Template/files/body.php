<body>
<?= $this->render('navheader'); ?>
<?= $this->page->isIndex()
    ? $this->render('toc')
    : '' ;
?>
<?= file_get_contents($this->page->getTargetFile()); ?>
<?= $this->render('navfooter'); ?>
</body>
