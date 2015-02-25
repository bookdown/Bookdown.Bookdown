<?php
use Aura\Html\Escaper as e;

$templates = $this->getViewRegistry();
$templates->set('navheader', __DIR__ . '/navheader.php');
$templates->set('navfooter', __DIR__ . '/navfooter.php');
$templates->set('toc', __DIR__ . '/toc.php');
?>
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
