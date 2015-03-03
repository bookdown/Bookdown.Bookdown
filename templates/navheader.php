<?php
$prev = $this->page->getPrev();
$parent = $this->page->getParent();
$next = $this->page->getNext();
?>

<nav class="navheader">
    <table>
        <tr>
            <th colspan="3" class="curr"><?php
                echo $this->page->getNumberAndTitle();
            ?></th>
        </tr>
        <tr>
            <td class="prev"><?php if ($prev) {
                echo $this->anchorRaw($prev->getHref(), $prev->getTitle());
            } ?></td>
            <td class="parent"><?php if ($parent) {
                echo $parent->getNumberAndTitle();
            } ?></th>
            <td class="next"><?php if ($next) {
                echo $this->anchorRaw($next->getHref(), $next->getTitle());
            } ?></td>
        </tr>
    </table>
</nav>
