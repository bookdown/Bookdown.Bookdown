<?php
$prev = $this->page->getPrev();
$parent = $this->page->getParent();
$next = $this->page->getNext();
?>

<nav class="navfooter">
    <table width="100%">
        <tr>
            <td width="40%" align="left"><?php if ($prev) {
                echo $this->anchorRaw($prev->getHref(), 'Prev');
            } ?></td>
            <td width="20%" align="center"><?php if ($parent) {
                echo $this->anchorRaw($parent->getHref(), 'Up');
            } ?></td>
            <td width="40%" align="right"><?php if ($next) {
                echo $this->anchorRaw($next->getHref(), 'Next');
            } ?></td>
        </tr>
        <tr>
            <td width="40%" align="left" valign="top"><?php if ($prev) {
                echo $prev->getNumberAndTitle();
            } ?></td>
            <td width="20%" align="center" valign="top"><?php if ($parent) {
                echo $parent->getNumberAndTitle();
            } ?></td>
            <td width="40%" align="right" valign="top"><?php if ($next) {
                echo $next->getNumberAndTitle();
            } ?></td>
        </tr>
    </table>
</nav>
