<body>
<?= $this->render('navheader'); ?>
<?= $this->page->isIndex()
    ? $this->render('toc')
    : '' ;
?>
<?= $this->html; ?>
<?= $this->render('navfooter'); ?>
</body>
