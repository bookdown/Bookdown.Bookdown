<?php use Aura\Html\Escaper as e; ?>
<html>
<head>
    <title><?= e::h($this->page->getTitle()); ?></title>
</head>
<body>

<?= $this->render('navheader'); ?>

<?= $this->page->isIndex()
    ? $this->render('toc')
    : '' ;
?>

<?= $this->html; ?>

<?= $this->render('navfooter'); ?>

</body>
</html>
