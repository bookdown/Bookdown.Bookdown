<?php
$prev = $this->page->getPrev();
$parent = $this->page->getParent();
$next = $this->page->getNext();
?>

<nav class="navfooter">
    <table>
        <tr>
            <td class="prev"><?php if ($prev) {
                echo $this->anchorRaw($prev->getHref(), 'Prev');
            } ?></td>
            <td class="parent"><?php if ($parent) {
                echo $this->anchorRaw($parent->getHref(), 'Up');
            } ?></td>
            <td class="next"><?php if ($next) {
                echo $this->anchorRaw($next->getHref(), 'Next');
            } ?></td>
        </tr>
        <tr>
            <td class="prev"><?php if ($prev) {
                echo $prev->getNumberAndTitle();
            } ?></td>
            <td class="parent"><?php if ($parent) {
                echo $parent->getNumberAndTitle();
            } ?></td>
            <td class="next"><?php if ($next) {
                echo $next->getNumberAndTitle();
            } ?></td>
        </tr>
    </table>
</nav>
