<?php
$views = $this->getViewRegistry();
$views->set('head', __DIR__ . '/head.php');
$views->set('body', __DIR__ . '/body.php');
$views->set('core', __DIR__ . '/core.php');
$views->set('navheader', __DIR__ . '/navheader.php');
$views->set('navfooter', __DIR__ . '/navfooter.php');
$views->set('toc', __DIR__ . '/toc.php');
?>
<html>
<?php echo $this->render('head'); ?>
<?php echo $this->render('body'); ?>
</html>
